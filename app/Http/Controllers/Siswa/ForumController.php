<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\KomentarForum;
use App\Models\PemetaanAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $user = Auth::guard('siswa')->user();

        $forums = Forum::with(['pembuat', 'pemetaanAkademik.mataPelajaran'])
            ->whereHas('pemetaanAkademik', function($q) use ($user) {
                $q->where('id_kelas', $user->id_kelas);
            })
            ->withCount('komentar')->latest()->paginate(15);

        return view('siswa.forums', [
            'data_forum' => $forums
        ]);
    }

    public function show($id)
    {
        $forum = Forum::with(['pembuat', 'pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran', 'komentar.pembuat'])
            ->findOrFail($id);
            
        return view('siswa.forums_show', [
            'forum' => $forum
        ]);
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate(['konten' => 'required|string']);
        
        KomentarForum::create([
            'id_forum'    => $id,
            'id_pembuat'  => Auth::guard('siswa')->id(),
            'tipe_pembuat' => \App\Models\Siswa::class,
            'konten'      => $request->konten,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim!');
    }

    public function destroy($id)
    {
        $forum = Forum::findOrFail($id);
        if ($forum->id_pembuat !== Auth::guard('siswa')->id() || $forum->tipe_pembuat !== \App\Models\Siswa::class) {
            abort(403);
        }
        $forum->delete();
        return back()->with('success', 'Diskusi dihapus!');
    }
}
