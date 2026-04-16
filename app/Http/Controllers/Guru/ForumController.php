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
        $guruId = Auth::guard('guru')->id();
        $forums = Forum::with(['pembuat', 'pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran'])
            ->whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', $guruId))
            ->withCount('komentar')->latest()->paginate(15);

        $pemetaanAkademik = PemetaanAkademik::with(['kelas', 'mataPelajaran'])
            ->where('id_guru', $guruId)->get();
            
        return view('guru.forums', [
            'data_forum' => $forums,
            'data_pemetaan' => $pemetaanAkademik
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pemetaan_akademik' => 'required|exists:pemetaan_akademik,id',
            'judul'   => 'required|string|max:255',
            'konten' => 'required|string',
        ]);

        Forum::create([
            'id_pemetaan_akademik' => $request->id_pemetaan_akademik,
            'id_pembuat' => Auth::guard('guru')->id(),
            'tipe_pembuat' => \App\Models\Guru::class,
            'judul'   => $request->judul,
            'konten' => $request->konten,
        ]);

        return back()->with('success', 'Diskusi baru berhasil dimulai!');
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
