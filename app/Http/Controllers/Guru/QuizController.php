<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\PertanyaanKuis;
use App\Models\PemetaanAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::guard('guru')->user();
        $quizzes = Kuis::with('pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran')
            ->whereHas('pemetaanAkademik', fn($q) => $q->where('id_guru', $user->id))
            ->latest()
            ->get();

        return view('guru.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $user = Auth::guard('guru')->user();
        $pemetaan = PemetaanAkademik::with('kelas', 'mataPelajaran')
            ->where('id_guru', $user->id)
            ->get();

        return view('guru.quizzes.create', compact('pemetaan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pemetaan_akademik' => 'required',
            'judul' => 'required|string|max:255',
            'tenggat_waktu' => 'required|date',
            'durasi_menit' => 'required|integer|min:1',
            'pertanyaan' => 'required|array|min:1',
            'pertanyaan.*' => 'required|string',
            'opsi_a.*' => 'required|string',
            'opsi_b.*' => 'required|string',
            'opsi_c.*' => 'required|string',
            'opsi_d.*' => 'required|string',
            'opsi_e.*' => 'required|string',
            'jawaban_benar.*' => 'required|in:A,B,C,D,E',
            'poin.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $quiz = Kuis::create([
                'id_pemetaan_akademik' => $request->id_pemetaan_akademik,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'tenggat_waktu' => $request->tenggat_waktu,
                'durasi_menit' => $request->durasi_menit,
            ]);

            foreach ($request->pertanyaan as $index => $text) {
                PertanyaanKuis::create([
                    'id_kuis' => $quiz->id,
                    'pertanyaan' => $text,
                    'opsi_a' => $request->opsi_a[$index],
                    'opsi_b' => $request->opsi_b[$index],
                    'opsi_c' => $request->opsi_c[$index],
                    'opsi_d' => $request->opsi_d[$index],
                    'opsi_e' => $request->opsi_e[$index],
                    'jawaban_benar' => $request->jawaban_benar[$index],
                    'poin' => $request->poin[$index],
                ]);
            }

            DB::commit();
            return redirect()->route('guru.quizzes.index')->with('success', 'Kuis berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Kuis $quiz)
    {
        $quiz->load(['pemetaanAkademik.kelas', 'pemetaanAkademik.mataPelajaran', 'pertanyaan', 'hasil.siswa']);
        return view('guru.quizzes.show', compact('quiz'));
    }

    public function destroy(Kuis $quiz)
    {
        $quiz->delete();
        return back()->with('success', 'Kuis berhasil dihapus!');
    }
}
