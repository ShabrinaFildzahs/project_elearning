<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Kuis;
use App\Models\HasilKuis;
use App\Models\PertanyaanKuis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::guard('siswa')->user();
        
        // Get quizzes for the student's class
        $quizzes = Kuis::with('pemetaanAkademik.mataPelajaran', 'pemetaanAkademik.guru')
            ->whereHas('pemetaanAkademik', fn($q) => $q->where('id_kelas', $user->id_kelas))
            ->latest()
            ->get();

        // Check which quizzes have been finished
        $finishedQuizIds = HasilKuis::where('id_siswa', $user->id)->pluck('id_kuis')->toArray();

        return view('siswa.quizzes.index', compact('quizzes', 'finishedQuizIds'));
    }

    public function show(Kuis $quiz)
    {
        $user = Auth::guard('siswa')->user();

        // Check if already finished
        if (HasilKuis::where('id_siswa', $user->id)->where('id_kuis', $quiz->id)->exists()) {
            return redirect()->route('siswa.quizzes.index')->with('error', 'Anda sudah mengerjakan kuis ini.');
        }

        $quiz->load('pertanyaan', 'pemetaanAkademik.mataPelajaran');
        return view('siswa.quizzes.show', compact('quiz'));
    }

    public function submit(Request $request, Kuis $quiz)
    {
        $user = Auth::guard('siswa')->user();

        // Prevent multiple submissions
        if (HasilKuis::where('id_siswa', $user->id)->where('id_kuis', $quiz->id)->exists()) {
            return redirect()->route('siswa.quizzes.index')->with('error', 'Anda sudah mengerjakan kuis ini.');
        }

        $pertanyaan = $quiz->pertanyaan;
        $totalSoal = $pertanyaan->count();
        $totalPoinTersedia = $pertanyaan->sum('poin');
        $poinDidapat = 0;
        $benar = 0;

        foreach ($pertanyaan as $q) {
            $jawabanSiswa = $request->input('jawaban_' . $q->id);
            if ($jawabanSiswa === $q->jawaban_benar) {
                $benar++;
                $poinDidapat += $q->poin;
            }
        }

        $salah = $totalSoal - $benar;
        $skor = ($totalPoinTersedia > 0) ? round(($poinDidapat / $totalPoinTersedia) * 100) : 0;

        HasilKuis::create([
            'id_kuis' => $quiz->id,
            'id_siswa' => $user->id,
            'skor' => $skor,
            'jumlah_benar' => $benar,
            'jumlah_salah' => $salah,
        ]);

        return redirect()->route('siswa.quizzes.index')->with('success', "Kuis selesai! Skor Anda: $skor");
    }
}
