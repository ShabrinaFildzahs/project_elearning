<?php

namespace App\Http\Controllers\Guru;

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
        $forums = Forum::with(['pembuat', 'pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran'])
            ->whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', Auth::guard('guru')->id()))
            ->withCount('komentar')->latest()->paginate(15);
            
        return view('guru.forums', [
            'data_forum' => $forums
        ]);
    }

    public function show($id)
    {
        $forum = Forum::with(['pembuat', 'pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran', 'komentar.pembuat'])
            ->findOrFail($id);
            
        return view('guru.forums_show', [
            'forum' => $forum
        ]);
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate(['konten' => 'required|string']);
        
        KomentarForum::create([
            'id_forum'    => $id,
            'id_pembuat'  => Auth::guard('guru')->id(),
            'tipe_pembuat' => \App\Models\Guru::class,
            'konten'      => $request->konten,
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function destroy($id)
    {
        Forum::findOrFail($id)->delete();
        return back()->with('success', 'Diskusi dihapus!');
    }
}
