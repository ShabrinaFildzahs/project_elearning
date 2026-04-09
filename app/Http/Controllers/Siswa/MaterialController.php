<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with(['academicMap.class', 'academicMap.subject', 'academicMap.teacher'])
            ->latest()->paginate(12);
        return view('siswa.materials', compact('materials'));
    }

    public function download(Material $material)
    {
        if (!Storage::disk('public')->exists($material->file_path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
        return Storage::disk('public')->download($material->file_path, $material->title . '.' . pathinfo($material->file_path, PATHINFO_EXTENSION));
    }
}
