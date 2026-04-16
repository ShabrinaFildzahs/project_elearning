<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $user = Auth::guard('siswa')->user();

        $materi = Materi::with(['pemetaanAkademik.mataPelajaran', 'pemetaanAkademik.guru'])
            ->whereHas('pemetaanAkademik', function($q) use ($user) {
                $q->where('id_kelas', $user->id_kelas);
            })
            ->latest()->paginate(12);

        return view('siswa.materials', [
            'data_materi' => $materi
        ]);
    }

    public function download($id)
    {
        $materi = Materi::findOrFail($id);
        
        if (!Storage::disk('public')->exists($materi->path_file)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        $extension = pathinfo($materi->path_file, PATHINFO_EXTENSION);
        $filename = $materi->judul . '.' . $extension;

        return Storage::disk('public')->download($materi->path_file, $filename);
    }
}
