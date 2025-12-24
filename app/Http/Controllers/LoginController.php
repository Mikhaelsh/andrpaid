<?php

namespace App\Http\Controllers;

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
            'email'            => 'required',
            'password'         => 'required'
        ]);

        $user = User::where('email',$validated['email'])->first();

        if($user && Hash::check($validated['password'], $user->password)) {

            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        return redirect('/login')->with('errorLogin','Invalid username or password.');
    }
}
