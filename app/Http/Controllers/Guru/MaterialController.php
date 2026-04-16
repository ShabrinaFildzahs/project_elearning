<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\PemetaanAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    private function getTeacherMaps()
    {
        return PemetaanAkademik::with(['kelas', 'mataPelajaran'])
            ->where('id_guru', Auth::guard('guru')->id())->get();
    }

    public function index()
    {
        $materi = Materi::with(['pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran'])
            ->whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', Auth::guard('guru')->id()))
            ->latest()->paginate(12);
            
        return view('guru.materials', [
            'data_materi' => $materi
        ]);
    }

    public function create()
    {
        $pemetaanAkademik = $this->getTeacherMaps();
        return view('guru.materials_form', [
            'materi' => null, 
            'data_pemetaan' => $pemetaanAkademik
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pemetaan_akademik' => 'required|exists:pemetaan_akademik,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|max:20480',
        ]);

        $path = $request->file('file')->store('materi', 'public');

        Materi::create([
            'id_pemetaan_akademik' => $request->id_pemetaan_akademik,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'path_file' => $path,
        ]);

        return redirect()->route('guru.materials.index')->with('success', 'Materi berhasil diunggah!');
    }

    public function edit($id)
    {
        $materi = Materi::findOrFail($id);
        $pemetaanAkademik = $this->getTeacherMaps();
        return view('guru.materials_form', [
            'materi' => $materi, 
            'data_pemetaan' => $pemetaanAkademik
        ]);
    }

    public function update(Request $request, $id)
    {
        $materi = Materi::findOrFail($id);
        
        $request->validate([
            'id_pemetaan_akademik' => 'required|exists:pemetaan_akademik,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $data = [
            'id_pemetaan_akademik' => $request->id_pemetaan_akademik,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('file')) {
            if ($materi->path_file) {
                Storage::disk('public')->delete($materi->path_file);
            }
            $data['path_file'] = $request->file('file')->store('materi', 'public');
        }

        $materi->update($data);
        return redirect()->route('guru.materials.index')->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $materi = Materi::findOrFail($id);
        if ($materi->path_file) {
            Storage::disk('public')->delete($materi->path_file);
        }
        $materi->delete();
        return back()->with('success', 'Materi berhasil dihapus!');
    }
}
