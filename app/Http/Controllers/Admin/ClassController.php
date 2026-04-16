<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PemetaanAkademik;
use App\Models\Guru;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount('pemetaanAkademik')->orderBy('nama')->get();
        $mataPelajaran = MataPelajaran::withCount('pemetaanAkademik')->orderBy('nama')->get();
        $pemetaanAkademik = PemetaanAkademik::with(['kelas', 'mataPelajaran', 'guru'])->get();
        $guru = Guru::orderBy('nama')->get();
        
        return view('admin.classes', [
            'data_kelas' => $kelas,
            'data_mapel' => $mataPelajaran,
            'data_pemetaan' => $pemetaanAkademik,
            'data_guru' => $guru
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255']);

        if ($request->tipe === 'mapel') {
            MataPelajaran::create(['nama' => $request->nama]);
            return back()->with('success', 'Mata pelajaran berhasil ditambahkan!');
        }

        Kelas::create(['nama' => $request->nama]);
        return back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama' => 'required|string|max:255']);

        if ($request->tipe === 'mapel') {
            MataPelajaran::findOrFail($id)->update(['nama' => $request->nama]);
            return back()->with('success', 'Mata pelajaran berhasil diperbarui!');
        }

        Kelas::findOrFail($id)->update(['nama' => $request->nama]);
        return back()->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy(Request $request, $id)
    {
        if ($request->tipe === 'mapel') {
            MataPelajaran::findOrFail($id)->delete();
            return back()->with('success', 'Mata pelajaran dihapus!');
        }

        Kelas::findOrFail($id)->delete();
        return back()->with('success', 'Kelas dihapus!');
    }

    // Plotting Guru ke Kelas & Mapel
    public function storeMap(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id',
            'id_mata_pelajaran' => 'required|exists:mata_pelajaran,id',
            'id_guru' => 'required|exists:guru,id',
        ]);

        PemetaanAkademik::firstOrCreate([
            'id_kelas' => $request->id_kelas,
            'id_mata_pelajaran' => $request->id_mata_pelajaran,
            'id_guru' => $request->id_guru,
        ]);

        return back()->with('success', 'Pemetaan guru berhasil disimpan!');
    }

    public function destroyMap($id)
    {
        PemetaanAkademik::findOrFail($id)->delete();
        return back()->with('success', 'Pemetaan akademik dihapus!');
    }
}
