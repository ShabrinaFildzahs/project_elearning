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
}
