<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\AcademicMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    private function getTeacherMaps()
    {
        return AcademicMap::with(['class', 'subject'])
            ->where('teacher_id', Auth::id())->get();
    }

    public function index()
    {
        $materials = Material::with(['academicMap.class', 'academicMap.subject'])
            ->whereHas('academicMap', fn($q) => $q->where('teacher_id', Auth::id()))
            ->latest()->paginate(12);
        return view('guru.materials', compact('materials'));
    }

    public function create()
    {
        $academicMaps = $this->getTeacherMaps();
        return view('guru.materials_form', ['material' => null, 'academicMaps' => $academicMaps]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_map_id' => 'required|exists:academic_maps,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:20480',
        ]);

        $path = $request->file('file')->store('materials', 'public');

        Material::create([
            'academic_map_id' => $request->academic_map_id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
        ]);

        return redirect()->route('guru.materials.index')->with('success', 'Materi berhasil diupload!');
    }

    public function edit(Material $material)
    {
        $academicMaps = $this->getTeacherMaps();
        return view('guru.materials_form', compact('material', 'academicMaps'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data = $request->only('title', 'description', 'academic_map_id');
        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($material->file_path);
            $data['file_path'] = $request->file('file')->store('materials', 'public');
        }

        $material->update($data);
        return redirect()->route('guru.materials.index')->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(Material $material)
    {
        Storage::disk('public')->delete($material->file_path);
        $material->delete();
        return back()->with('success', 'Materi berhasil dihapus!');
    }
}
