<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function register(){
        return view('auth.register');
    }

    function login(){
        return view('auth.login');
    }
    
    function register_proses(Request $request){
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return view('auth.login');;
    }

    function login_proses(Request $request){
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            return redirect('/admin')->with('success', 'Login Success');
        }

        return back()->with('error', 'Credentials error');
    }

    function logout(){

        Auth::logout();
    
        return redirect('/');
    }

    function admin(){
        return view('admin.dashboard-admin');;
    }
}
