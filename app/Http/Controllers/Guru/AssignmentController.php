<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\Pengumpulan;
use App\Models\PemetaanAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    private function getTeacherMaps()
    {
        return PemetaanAkademik::with(['kelas', 'mataPelajaran'])
            ->where('id_guru', Auth::guard('guru')->id())->get();
    }

    public function index()
    {
        $tugas = Tugas::with(['pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran'])
            ->whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', Auth::guard('guru')->id()))
            ->withCount('pengumpulan')->latest()->paginate(12);
            
        return view('guru.assignments', [
            'data_tugas' => $tugas
        ]);
    }

    public function create()
    {
        $pemetaanAkademik = $this->getTeacherMaps();
        return view('guru.assignments_form', [
            'tugas' => null, 
            'data_pemetaan' => $pemetaanAkademik
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pemetaan_akademik' => 'required|exists:pemetaan_akademik,id',
            'judul'           => 'required|string|max:255',
            'deskripsi'     => 'required|string',
            'tenggat_waktu'        => 'required|date',
            'tipe'            => 'required|in:tugas,kuis',
        ]);

        Tugas::create([
            'id_pemetaan_akademik' => $request->id_pemetaan_akademik,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tenggat_waktu' => $request->tenggat_waktu,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('guru.assignments.index')->with('success', 'Tugas berhasil dibuat!');
    }

    public function edit($id)
    {
        $tugas = Tugas::findOrFail($id);
        $pemetaanAkademik = $this->getTeacherMaps();
        return view('guru.assignments_form', [
            'tugas' => $tugas, 
            'data_pemetaan' => $pemetaanAkademik
        ]);
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        $request->validate([
            'id_pemetaan_akademik' => 'required|exists:pemetaan_akademik,id',
            'judul'       => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tenggat_waktu'    => 'required|date',
            'tipe'        => 'required|in:tugas,kuis',
        ]);

        $tugas->update([
            'id_pemetaan_akademik' => $request->id_pemetaan_akademik,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tenggat_waktu' => $request->tenggat_waktu,
            'tipe' => $request->tipe,
        ]);

        return redirect()->route('guru.assignments.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Tugas::findOrFail($id)->delete();
        return back()->with('success', 'Tugas berhasil dihapus!');
    }

    public function submissions($id)
    {
        $tugas = Tugas::findOrFail($id);
        $pengumpulan = Pengumpulan::with('siswa')
            ->where('id_tugas', $id)->get();
            
        return view('guru.submissions', [
            'tugas' => $tugas, 
            'data_pengumpulan' => $pengumpulan
        ]);
    }

    public function grade(Request $request, $id)
    {
        $pengumpulan = Pengumpulan::findOrFail($id);
        $request->validate(['nilai' => 'required|integer|min:0|max:100']);
        
        $pengumpulan->update([
            'nilai' => $request->nilai, 
            'status' => 'dinilai'
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }
}
