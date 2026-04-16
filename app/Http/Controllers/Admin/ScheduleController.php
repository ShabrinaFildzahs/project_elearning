<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\PemetaanAkademik;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $jadwal = Jadwal::with(['pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran', 'pemetaanAkademik.guru'])
            ->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai')
            ->get();
        
        $pemetaanAkademik = PemetaanAkademik::with(['kelas', 'mataPelajaran', 'guru'])->get();
        
        return view('admin.schedules', [
            'data_jadwal' => $jadwal,
            'data_pemetaan' => $pemetaanAkademik
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pemetaan_akademik' => 'required|exists:pemetaan_akademik,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        Jadwal::create([
            'id_pemetaan_akademik' => $request->id_pemetaan_akademik,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pemetaan_akademik' => 'required|exists:pemetaan_akademik,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update([
            'id_pemetaan_akademik' => $request->id_pemetaan_akademik,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return back()->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal berhasil dihapus!');
    }
}
