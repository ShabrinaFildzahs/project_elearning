<?php

namespace App\Http\Controllers\Guru;

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
            ->whereHas('academicMap', fn($q) => $q->where('teacher_id', Auth::id()))
            ->withCount('comments')->latest()->paginate(15);
        return view('guru.forums', compact('forums'));
    }

    public function show(Forum $forum)
    {
        $forum->load(['user', 'academicMap.class', 'academicMap.subject', 'comments.user']);
        return view('guru.forums_show', compact('forum'));
    }

    public function storeComment(Request $request, Forum $forum)
    {
        $request->validate(['content' => 'required|string']);
        ForumComment::create([
            'forum_id' => $forum->id,
            'user_id'  => Auth::id(),
            'content'  => $request->content,
        ]);
        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function destroy(Forum $forum)
    {
        $forum->delete();
        return back()->with('success', 'Diskusi dihapus!');
    }
}
