<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view("pages.login");
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
          
            if(!$user->isAdmin()){
                ActivityLog::create([
                    "user_id" => $user->id,
                    "type" => "login"
                ]);
            }

            return redirect()->route('dashboard', [
                'profileId' => Auth::user()->profileId
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }
          
    public function indexForgotPassword(){
        return view("pages.login-forgotPassword");
    }

    public function resetPassword(Request $request){
        $validated = $request->validate([
            'email'            => 'required|email|exists:users,email',
            'password'         => 'required|min:8|same:password_confirmation',
            'password_confirmation'  => 'required',
        ]);

        $user = User::where("email", $validated["email"])->first();

        if(!$user->isAdmin()){
            $user->update([
                "password" => bcrypt($validated["password"]),
                "latest_password_updated_at" => now()
            ]);
        }

        return redirect("/login")->with('success', 'Your password has been changed successfully!');
    }

    public function logoutUser(Request $request) {
        if(!Auth::user()->isAdmin()){
            ActivityLog::create([
                "user_id" => Auth::user()->id,
                "type" => "logout"
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return redirect('/');
    }
}