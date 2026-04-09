<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\AcademicMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    private function getTeacherMaps()
    {
        return AcademicMap::with(['class', 'subject'])->where('teacher_id', Auth::id())->get();
    }

    public function index()
    {
        $assignments = Assignment::with(['academicMap.class', 'academicMap.subject'])
            ->whereHas('academicMap', fn($q) => $q->where('teacher_id', Auth::id()))
            ->withCount('submissions')->latest()->paginate(12);
        return view('guru.assignments', compact('assignments'));
    }

    public function create()
    {
        $academicMaps = $this->getTeacherMaps();
        return view('guru.assignments_form', ['assignment' => null, 'academicMaps' => $academicMaps]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_map_id' => 'required|exists:academic_maps,id',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'deadline'        => 'required|date',
            'type'            => 'required|in:tugas,kuis',
        ]);

        Assignment::create($request->only('academic_map_id', 'title', 'description', 'deadline', 'type'));
        return redirect()->route('guru.assignments.index')->with('success', 'Tugas berhasil dibuat!');
    }

    public function edit(Assignment $assignment)
    {
        $academicMaps = $this->getTeacherMaps();
        return view('guru.assignments_form', compact('assignment', 'academicMaps'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'deadline'    => 'required|date',
            'type'        => 'required|in:tugas,kuis',
        ]);

        $assignment->update($request->only('title', 'description', 'deadline', 'type', 'academic_map_id'));
        return redirect()->route('guru.assignments.index')->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Tugas berhasil dihapus!');
    }

    public function submissions(Assignment $assignment)
    {
        $submissions = Submission::with('student')
            ->where('assignment_id', $assignment->id)->get();
        return view('guru.submissions', compact('assignment', 'submissions'));
    }

    public function grade(Request $request, Submission $submission)
    {
        $request->validate(['grade' => 'required|integer|min:0|max:100']);
        $submission->update(['grade' => $request->grade, 'status' => 'graded']);
        return back()->with('success', 'Nilai berhasil disimpan!');
    }
}
