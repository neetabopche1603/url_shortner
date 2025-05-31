<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // URL SHORTENER Auth : Login User Page

    public function login()
    {
        return view('Auth.login');
    }

    // URL SHORTENER Auth : Login

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);
        try {

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                return redirect()->route('dashboard')->with('success', 'You have Successfully loggedin');
            }
            return redirect()->route('login')->with('error', 'Oppes! You have entered invalid credentials');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', "Login Failed " . $e->getMessage());
        }
    }


    // URL SHORTENER Auth : Register User Page
    public function register()
    {
        return view('Auth.register');
    }


    //URL SHORTENER Auth : Register
    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'You have Successfully loggedin');
        } catch (Exception $e) {
            return redirect()->back()->with('error', "Registration Failed" . $e->getMessage());
        }
    }

    // URL SHORTENER Auth : User Logout
    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have Successfully logout');
    }
}
