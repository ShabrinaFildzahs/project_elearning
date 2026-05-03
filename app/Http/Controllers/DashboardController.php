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
        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $today = $hariMap[now()->format('l')] ?? '';

        if (Auth::guard('admin')->check()) {
            $stats = [
                'guru'  => Guru::count(),
                'siswa' => Siswa::count(),
                'kelas' => Kelas::count(),
                'mapel' => MataPelajaran::count(),
            ];
            return view('dashboards.admin', compact('stats'));
        }

        if (Auth::guard('guru')->check()) {
            $user = Auth::guard('guru')->user();
            
            // Ambil jadwal mengajar hari ini
            $jadwal_hari_ini = \App\Models\Jadwal::with(['pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran'])
                ->whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', $user->id))
                ->where('hari', $today)
                ->orderBy('jam_mulai')
                ->get();

            $stats = [
                'materi' => \App\Models\Materi::whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', $user->id))->count(),
                'tugas'  => \App\Models\Tugas::whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', $user->id))->count(),
            ];

            return view('dashboards.guru', compact('user', 'jadwal_hari_ini', 'today', 'stats'));
        }

        if (Auth::guard('siswa')->check()) {
            $user = Auth::guard('siswa')->user();

            // Ambil jadwal pelajaran hari ini
            $jadwal_hari_ini = \App\Models\Jadwal::with(['pemetaanAkademik.mataPelajaran', 'pemetaanAkademik.guru'])
                ->whereHas('pemetaanAkademik', fn($q) => $q->where('id_kelas', $user->id_kelas))
                ->where('hari', $today)
                ->orderBy('jam_mulai')
                ->get();

            return view('dashboards.siswa', compact('user', 'jadwal_hari_ini', 'today'));
        }

        abort(403, 'Sesi tidak valid');
    }
}
