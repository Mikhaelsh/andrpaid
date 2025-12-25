<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Province;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function index(){
        return view("pages.register", ["type" => "selectRole"]);
    }

    public function indexRole($role){
        $provinces = Province::all();

        return view("pages.register",[
            "type" => "specificRole",
            "role" => $role,
            "provinces" => $provinces
        ]);
    }

    public function insertNewUser($role, Request $request){
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email',
            'description'      => 'nullable|string|max:500',
            'province'         => 'required',
            'password'         => 'required|min:8|same:confirmPassword',
            'confirmPassword'  => 'required',
        ]);

        $user = User::create([
            'name'=> $validated['name'],
            'email'=> $validated['email'],
            'password' => bcrypt($validated['password']),
            'description'=> $validated['description']
        ]);

        $province = Province::where('provinceId', $validated['province'] )->first();

        if($role === "lecturer"){
            Lecturer::create([
                "user_id"=> $user->id,
                "province_id" => $province->id
            ]);
        } else{
            University::create([
                "user_id"=> $user->id,
                "province_id" => $province->id
            ]);
        }

        return redirect("/login")->with('success', 'Your account has been created successfully!');
    }
}
