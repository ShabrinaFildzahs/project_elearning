<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['academicMap.class', 'academicMap.subject'])
            ->withCount('submissions')
            ->orderBy('deadline')
            ->paginate(12);

        $mySubmissions = Submission::where('student_id', Auth::id())
            ->pluck('assignment_id')
            ->toArray();

        return view('siswa.assignments', compact('assignments', 'mySubmissions'));
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['academicMap.class', 'academicMap.subject', 'academicMap.teacher']);
        $submission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->first();
        return view('siswa.assignments_show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate(['file' => 'required|file|max:20480']);

        $existing = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())->first();

        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
            $existing->update([
                'file_path' => $request->file('file')->store('submissions', 'public'),
                'status' => 'pending',
                'grade' => null,
            ]);
        } else {
            Submission::create([
                'assignment_id' => $assignment->id,
                'student_id'   => Auth::id(),
                'file_path'    => $request->file('file')->store('submissions', 'public'),
                'status'       => 'pending',
            ]);
        }

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
