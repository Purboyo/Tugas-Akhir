<?php

namespace App\Http\Controllers;

// app/Http/Controllers/AuthController.php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            if (auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif (auth::user()->role === 'teknisi') {
                return redirect()->intended('/teknisi/dashboard');
            } elseif (auth::user()->role === 'kepala_lab') {
                return redirect()->intended('/kepala_lab/dashboard');
            }

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
