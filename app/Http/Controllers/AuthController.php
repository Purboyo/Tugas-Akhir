<?php

namespace App\Http\Controllers;

// app/Http/Controllers/AuthController.php
use Illuminate\Support\Facades\Auth;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    // Menampilkan halaman login
    public function loginForm() {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request) {
        // Ambil hanya email dan password dari request
        $credentials = $request->only('email', 'password');

        // Cek kredensial, jika cocok maka login
        if (Auth::attempt($credentials)) {
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Redirect sesuai role user yang login
            if (auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif (auth::user()->role === 'teknisi') {
                return redirect()->intended('/teknisi/dashboard');
            } elseif (auth::user()->role === 'kepala_lab') {
                return redirect()->intended('/kepala_lab/dashboard');
            } elseif (auth::user()->role === 'jurusan') {
                return redirect()->intended('/jurusan/dashboard');
            }

            // Jika role tidak dikenali, redirect default
            return redirect()->intended('/dashboard');
        }

        // Jika login gagal, kembali ke halaman login dengan error
        return back()->withErrors([
            'email' => 'Email or password is incorrect.',
        ]);
    }

    // Proses logout
    public function logout(Request $request) {
        Auth::logout(); // Logout user
        $request->session()->invalidate(); // Hapus session
        $request->session()->regenerateToken(); // Buat token baru

        return redirect('/'); // Redirect ke halaman utama
    }

    public function forgotPasswordForm() {
    return view('auth.forgot-password');
    }
    public function forgotPassword(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(60);

        // Simpan token ke tabel password_resets
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Buat link reset
        $resetLink = url('/reset-password?token=' . $token . '&email=' . urlencode($request->email));

        // Kirim email
        Mail::to($request->email)->send(new ForgotPasswordMail($resetLink));

        return back()->with('status', 'Link reset password alredy sent to your email.');
    }

    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email
        ]);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        // Cek apakah token valid
        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Token invalid.']);
        }

        // Update password user
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);


        // Hapus token setelah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password changed. Please login.');
    }

}