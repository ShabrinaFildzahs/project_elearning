@extends('layouts.app')
@section('title', 'Kuis Interaktif')
@section('page_title', 'Kuis Pilihan Ganda')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Kuis</h3>
            <p class="text-sm text-slate-500">Kerjakan kuis interaktif dan kumpulkan skor terbaikmu</p>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl font-bold animate-in fade-in slide-in-from-top-2">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl font-bold animate-in fade-in slide-in-from-top-2">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
        @php $isFinished = in_array($quiz->id, $finishedQuizIds); @endphp
        <div class="glass-card rounded-2xl overflow-hidden flex flex-col hover:shadow-xl transition-all border border-slate-100 group {{ $isFinished ? 'opacity-75' : '' }}">
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-purple-100 text-purple-600 text-[10px] font-black uppercase rounded-lg">
                        {{ $quiz->pemetaanAkademik->mataPelajaran->nama ?? 'Mapel' }}
                    </span>
                    @if($isFinished)
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase rounded-lg flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        Selesai
                    </span>
                    @endif
                </div>
                <h4 class="font-bold text-slate-800 mb-2 group-hover:text-purple-600 transition-colors">{{ $quiz->judul }}</h4>
                <p class="text-xs text-slate-500 line-clamp-2 mb-4">{{ $quiz->deskripsi ?? 'Klik tombol di bawah untuk mulai mengerjakan kuis ini.' }}</p>
                
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-[11px] text-slate-400 font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Guru: {{ $quiz->pemetaanAkademik->guru->nama ?? '-' }}
                    </div>
                    <div class="flex items-center gap-2 text-[11px] text-slate-400 font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tenggat: {{ $quiz->tenggat_waktu->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                <span class="text-[10px] font-bold text-slate-400 uppercase">
                    {{ $quiz->durasi_menit }} Menit · {{ $quiz->pertanyaan()->count() }} Soal
                </span>
                @if($isFinished)
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black text-slate-400">Skor Anda:</span>
                        <span class="px-2 py-0.5 rounded bg-emerald-500 text-white text-xs font-black">{{ App\Models\HasilKuis::where('id_siswa', Auth::guard('siswa')->id())->where('id_kuis', $quiz->id)->first()->skor }}</span>
                    </div>
                @else
                    <a href="{{ route('siswa.quizzes.show', $quiz->id) }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-black rounded-xl transition shadow-lg shadow-purple-200">
                        Mulai Kuis
                    </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
            </div>
            <p class="text-slate-500 font-bold">Tidak ada kuis yang aktif untukmu.</p>
            <p class="text-xs text-slate-400 mt-1">Kembali lagi nanti untuk melihat kuis baru.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
