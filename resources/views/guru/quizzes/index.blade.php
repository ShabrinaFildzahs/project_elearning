@extends('layouts.app')
@section('title', 'Manajemen Kuis')
@section('page_title', 'Kuis Pilihan Ganda')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Daftar Kuis</h3>
            <p class="text-sm text-slate-500">Kelola soal pilihan ganda dan lihat hasil skor siswa</p>
        </div>
        <a href="{{ route('guru.quizzes.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-lg shadow-emerald-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Kuis Baru
        </a>
    </div>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl font-bold animate-in fade-in slide-in-from-top-2">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
        <div class="glass-card rounded-2xl overflow-hidden flex flex-col hover:shadow-xl transition-all border border-slate-100 group">
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase rounded-lg">
                        {{ $quiz->pemetaanAkademik->mataPelajaran->nama ?? 'Mapel' }}
                    </span>
                    <form action="{{ route('guru.quizzes.destroy', $quiz->id) }}" method="POST" onsubmit="return confirm('Hapus kuis ini?')">
                        @csrf @method('DELETE')
                        <button class="text-slate-300 hover:text-red-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
                <h4 class="font-bold text-slate-800 mb-2 group-hover:text-emerald-600 transition-colors">{{ $quiz->judul }}</h4>
                <p class="text-xs text-slate-500 line-clamp-2 mb-4">{{ $quiz->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-[11px] text-slate-400 font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Kelas: {{ $quiz->pemetaanAkademik->kelas->nama ?? '-' }}
                    </div>
                    <div class="flex items-center gap-2 text-[11px] text-slate-400 font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Deadline: {{ $quiz->tenggat_waktu->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>
            </div>
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                <span class="text-[10px] font-bold text-slate-400 uppercase">
                    {{ $quiz->pertanyaan_count ?? $quiz->pertanyaan()->count() }} Pertanyaan
                </span>
                <a href="{{ route('guru.quizzes.show', $quiz->id) }}" class="text-xs font-black text-emerald-600 hover:underline">
                    Lihat Hasil Skor →
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
            </div>
            <p class="text-slate-500 font-bold">Belum ada kuis yang dibuat.</p>
            <p class="text-xs text-slate-400 mt-1">Mulai buat kuis pilihan ganda untuk siswamu.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
