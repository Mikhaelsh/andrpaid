<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index(){
        $user = Auth::user();

        $province = $user->isLecturer() ? $user->lecturer->province : $user->university->province;

        $allProvinces = Province::all();

        return view("pages.settings", [
            "user"=> $user,
            "province" => $province,
            "allProvinces" => $allProvinces,
        ]);
    }

    public function updatePublicProfile(Request $request){
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'province_id'    => 'required|string',
        ]);

        $user = Auth::user();

        if($user->isLecturer()){
            $accRole = $user->lecturer;
        } else{
            $accRole = $user->university;
        }

        $province = Province::where("provinceId", $validated["province_id"])->first();

        $accRole->update([
            "province_id" => $province->id
        ]);

        $user->update([
            "name" => $validated["name"],
            "description"=> $validated["description"],
            "provinceId"=> $validated["province_id"],
        ]);

        return redirect("/settings#profile")->with("success", "Your public profile has been updated successfully!");
    }

    public function updateEmail(Request $request){
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string',
        ]);

        if ($validator->fails() || !Hash::check($request->password, $user->password)) {
            return redirect("/settings#account")->with("error", "Update failed. Invalid password or unavailable email address.");
        }

        $user->update([
            "email" => $request->email,
        ]);

        return redirect("/settings#account")->with("success", "Your email has been updated successfully!");
    }

    public function updatePassword(Request $request){
        $user = Auth::user();

        $validated = $request->validate([
            'current_password'  => 'required|string|max:255',
            'new_password'      => 'required|string',
            'new_password_confirmation'    => 'required|string',
        ]);

        if (!Hash::check($validated["current_password"], $user->password) || $validated["new_password"] !== $validated["new_password_confirmation"]) {
            return redirect("/settings#account")->with("error", "Update failed. Current password incorrect or new passwords do not match.");
        }

        $user->update([
            'password' => bcrypt($validated['new_password']),
            'latest_password_updated_at' => now(),
        ]);

        return redirect("/settings#account")->with("success", "Your password has been changed successfully!");
    }

    public function deleteAccount(Request $request){
        $user = Auth::user();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();


        $user->delete();

        return redirect('/');
    }
}
