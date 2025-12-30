<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showSiswaLoginForm()
    {
        return view('auth.login_siswa');
    }

    public function login(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        if (config('recaptcha.api_site_key')) {
            $rules['g-recaptcha-response'] = 'recaptcha';
        }

        $request->validate($rules);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->hasRole('guru')) {
                return redirect()->intended('/guru/dashboard');
            } elseif ($user->hasRole('siswa')) {
                return redirect()->intended('/siswa/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
