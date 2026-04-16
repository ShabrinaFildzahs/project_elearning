<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        $jadwal = Jadwal::with(['pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran'])
            ->whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', Auth::guard('guru')->id()))
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('guru.schedules', [
            'data_jadwal' => $jadwal,
            'hari_list' => $hari_list
        ]);
    }
}
