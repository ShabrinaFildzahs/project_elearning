<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumComment;
use App\Models\AcademicMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $forums = Forum::with(['user', 'academicMap.class', 'academicMap.subject'])
            ->withCount('comments')->latest()->paginate(15);
        $academicMaps = AcademicMap::with(['class', 'subject'])->get();
        return view('siswa.forums', compact('forums', 'academicMaps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_map_id' => 'required|exists:academic_maps,id',
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Forum::create([
            'academic_map_id' => $request->academic_map_id,
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Diskusi berhasil dibuat!');
    }

    public function show(Forum $forum)
    {
        $forum->load(['user', 'academicMap.class', 'academicMap.subject', 'comments.user']);
        return view('siswa.forums_show', compact('forum'));
    }

    public function storeComment(Request $request, Forum $forum)
    {
        $request->validate(['content' => 'required|string']);
        ForumComment::create([
            'forum_id' => $forum->id,
            'user_id'  => Auth::id(),
            'content'  => $request->content,
        ]);
        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    public function destroy(Forum $forum)
    {
        if ($forum->user_id !== Auth::id()) abort(403);
        $forum->delete();
        return back()->with('success', 'Diskusi dihapus!');
    }
}
