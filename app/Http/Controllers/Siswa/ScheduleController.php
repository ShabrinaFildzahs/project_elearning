<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $user = Auth::guard('siswa')->user();

        $jadwal = Jadwal::with(['pemetaanAkademik.mataPelajaran', 'pemetaanAkademik.guru'])
            ->whereHas('pemetaanAkademik', function($q) use ($user) {
                $q->where('id_kelas', $user->id_kelas);
            })
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('siswa.schedules', [
            'data_jadwal' => $jadwal,
            'hari_list' => $hari_list
        ]);
    }
}
