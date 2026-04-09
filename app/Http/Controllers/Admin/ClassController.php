<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Subject;
use App\Models\AcademicMap;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Kelas::withCount('academicMaps')->orderBy('name')->get();
        $subjects = Subject::withCount('academicMaps')->orderBy('name')->get();
        $academicMaps = AcademicMap::with(['class', 'subject', 'teacher'])->get();
        $teachers = User::where('role', 'guru')->orderBy('name')->get();
        return view('admin.classes', compact('classes', 'subjects', 'academicMaps', 'teachers'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        if ($request->type === 'subject') {
            Subject::create(['name' => $request->name]);
            return back()->with('success', 'Mata pelajaran berhasil ditambahkan!');
        }

        Kelas::create(['name' => $request->name]);
        return back()->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);

        if ($request->type === 'subject') {
            Subject::findOrFail($id)->update(['name' => $request->name]);
            return back()->with('success', 'Mata pelajaran berhasil diperbarui!');
        }

        Kelas::findOrFail($id)->update(['name' => $request->name]);
        return back()->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        if (request()->type === 'subject') {
            Subject::findOrFail($id)->delete();
            return back()->with('success', 'Mata pelajaran dihapus!');
        }

        Kelas::findOrFail($id)->delete();
        return back()->with('success', 'Kelas dihapus!');
    }

    // Store Academic Map (link guru-mapel-kelas)
    public function storeMap(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
        ]);

        AcademicMap::firstOrCreate([
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
        ]);

        return back()->with('success', 'Pemetaan guru berhasil disimpan!');
    }
}
