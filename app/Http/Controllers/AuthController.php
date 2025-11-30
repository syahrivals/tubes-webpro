<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login
        $email = $request->email;
        $password = $request->password;
        $remember = $request->has('remember');
        
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            $request->session()->regenerate();
            
            // Cek role user dan redirect
            $user = auth()->user();
            if ($user->role == 'dosen') {
                return redirect()->route('dosen.dashboard');
            } else {
                return redirect()->route('mahasiswa.dashboard');
            }
        }

        // Jika login gagal, kembali dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    public function logout(Request $request)
    {
        // Logout user
        Auth::logout();
        
        // Hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Redirect ke halaman login
        return redirect()->route('login');
    }
}

