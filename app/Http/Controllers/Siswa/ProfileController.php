<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('siswa')->user();
        $user->load('kelas'); // Ensure class info is loaded
        return view('siswa.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('siswa')->user();
        
        $request->validate([
            'email' => 'required|email|unique:siswa,email,' . $user->id,
            'no_hp' => 'required',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // Update data dasar
        $user->email = $request->email;
        $user->no_hp = $request->no_hp;

        // Update password jika diisi
        if ($request->filled('new_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
            }
            $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
