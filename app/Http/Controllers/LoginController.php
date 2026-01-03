<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index(){
        return view("pages.login");
    }

    public function loginUser(Request $request){
        $validated = $request->validate([
            'email'     => 'required',
            'password'  => 'required'
        ]);

        $user = User::where('email',$validated['email'])->first();

        if($user && Hash::check($validated['password'], $user->password)) {

            Auth::login($user);
            $request->session()->regenerate();

            if(!$user->isAdmin()){
                ActivityLog::create([
                    "user_id" => $user->id,
                    "type" => "login"
                ]);
            }

            return redirect('/dashboard');
        }

        return redirect('/login')->with('errorLogin','Invalid username or password.');
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
