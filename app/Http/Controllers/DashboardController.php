<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index()
    {
        if (Auth::guard('admin')->check()) {
            $stats = [
                'guru' => Guru::count(),
                'siswa' => Siswa::count(),
                'kelas' => Kelas::count(),
                'mapel' => MataPelajaran::count(),
            ];
            return view('dashboards.admin', compact('stats'));
        }

        if (Auth::guard('guru')->check()) {
            $user = Auth::guard('guru')->user();
            return view('dashboards.guru', compact('user'));
        }

        if (Auth::guard('siswa')->check()) {
            $user = Auth::guard('siswa')->user();
            return view('dashboards.siswa', compact('user'));
        }

        abort(403, 'Sesi tidak valid');
    }
}
