<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function showRegisterForm(){
        return view('auth.register');
    }
    public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' =>'required|string|min:8|confirmed',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);
        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }
    public function showLoginForm(){
        return view('auth.login');
    }
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]); 
        if (Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.reports.index');
            } else {
                return redirect()->route('home'); 
            }
        }
        return back()->withErrors(['email' => 'Invalid information.']);
    }
    public function logout(Request $request){
        Auth::logout();
        return redirect()->route('home');
    }
}
