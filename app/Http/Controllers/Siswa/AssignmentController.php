<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Pengumpulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::guard('siswa')->user();

        $tugas = Tugas::with(['pemetaanAkademik.mataPelajaran'])
            ->whereHas('pemetaanAkademik', function($q) use ($user) {
                $q->where('id_kelas', $user->id_kelas);
            })
            ->withCount(['pengumpulan' => function($q) use ($user) {
                $q->where('id_siswa', $user->id);
            }])
            ->orderBy('tenggat_waktu')
            ->paginate(12);

        $idTugasSudahDikirim = Pengumpulan::where('id_siswa', $user->id)
            ->pluck('id_tugas')
            ->toArray();

        return view('siswa.assignments', [
            'data_tugas' => $tugas, 
            'idTugasSudahDikirim' => $idTugasSudahDikirim
        ]);
    }

    public function show($id)
    {
        $tugas = Tugas::with(['pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran', 'pemetaanAkademik.guru'])
            ->findOrFail($id);
            
        $pengumpulan = Pengumpulan::where('id_tugas', $id)
            ->where('id_siswa', Auth::guard('siswa')->id())
            ->first();

        return view('siswa.assignments_show', [
            'tugas' => $tugas, 
            'pengumpulan' => $pengumpulan
        ]);
    }

    public function submit(Request $request, $id)
    {
        $request->validate(['file' => 'required|file|max:20480']);
        $siswa_id = Auth::guard('siswa')->id();

        $existing = Pengumpulan::where('id_tugas', $id)
            ->where('id_siswa', $siswa_id)->first();

        if ($existing) {
            if ($existing->path_file) {
                Storage::disk('public')->delete($existing->path_file);
            }
            $existing->update([
                'path_file' => $request->file('file')->store('pengumpulan', 'public'),
                'status' => 'dikirim',
                'nilai' => null,
            ]);
        } else {
            Pengumpulan::create([
                'id_tugas' => $id,
                'id_siswa' => $siswa_id,
                'path_file' => $request->file('file')->store('pengumpulan', 'public'),
                'status' => 'dikirim',
            ]);
        }

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
