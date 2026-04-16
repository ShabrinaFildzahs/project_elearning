<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('login', function (Request $request) {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('password');
        $loginValue = $request->input('login');

        // 1. Cek Guard Admin (menggunakan username)
        if (Auth::guard('admin')->attempt(['username' => $loginValue, 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // 2. Cek Guard Guru (menggunakan email)
        if (Auth::guard('guru')->attempt(['email' => $loginValue, 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // 3. Cek Guard Siswa (menggunakan email)
        if (Auth::guard('siswa')->attempt(['email' => $loginValue, 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('login');
    });
});

Route::post('logout', function (Request $request) {
    // Logout dari semua guard yang mungkin aktif
    Auth::guard('admin')->logout();
    Auth::guard('guru')->logout();
    Auth::guard('siswa')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');
