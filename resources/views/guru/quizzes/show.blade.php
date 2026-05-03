@extends('layouts.app')
@section('title', 'Detail Kuis')
@section('page_title', 'Hasil & Detail Kuis')

@section('content')
<div class="space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('guru.quizzes.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-emerald-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h3 class="text-xl font-bold text-slate-800">{{ $quiz->judul }}</h3>
            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">{{ $quiz->pemetaanAkademik->mataPelajaran->nama }} · {{ $quiz->pemetaanAkademik->kelas->nama }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Results Leaderboard --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-3xl p-8 border border-slate-100 bg-white">
                <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Peringkat & Hasil Siswa</h4>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-slate-50">
                                <th class="pb-4 text-[10px] font-black text-slate-300 uppercase tracking-widest">Siswa</th>
                                <th class="pb-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-center">Benar</th>
                                <th class="pb-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-center">Salah</th>
                                <th class="pb-4 text-[10px] font-black text-slate-300 uppercase tracking-widest text-right">Skor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($quiz->hasil as $hasil)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($hasil->siswa->nama_lengkap, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-700">{{ $hasil->siswa->nama_lengkap }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $hasil->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="text-xs font-bold text-emerald-600">{{ $hasil->jumlah_benar }}</span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="text-xs font-bold text-red-400">{{ $hasil->jumlah_salah }}</span>
                                </td>
                                <td class="py-4 text-right">
                                    <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-black text-sm">
                                        {{ $hasil->skor }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 font-medium italic">
                                    Belum ada siswa yang mengerjakan kuis ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Quiz Info Side --}}
        <div class="space-y-6">
            <div class="glass-card rounded-3xl p-6 border border-slate-100 bg-white shadow-sm">
                <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-6">Informasi Kuis</h4>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400">Total Soal</span>
                        <span class="text-sm font-black text-slate-700">{{ $quiz->pertanyaan->count() }} Soal</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400">Durasi</span>
                        <span class="text-sm font-black text-slate-700">{{ $quiz->durasi_menit }} Menit</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-400">Pengerjaan</span>
                        <span class="text-sm font-black text-emerald-600">{{ $quiz->hasil->count() }} Siswa</span>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6 border border-slate-100 bg-white shadow-sm overflow-hidden">
                <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-6">Review Soal</h4>
                <div class="space-y-6 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($quiz->pertanyaan as $index => $q)
                    <div class="pb-6 border-b border-slate-50 last:border-0">
                        <p class="text-xs font-black text-slate-400 mb-2">Soal #{{ $index + 1 }}</p>
                        <p class="text-sm font-bold text-slate-700 mb-3">{{ $q->pertanyaan }}</p>
                        <div class="space-y-1">
                            <p class="text-[11px] font-medium {{ $q->jawaban_benar == 'A' ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">A. {{ $q->opsi_a }}</p>
                            <p class="text-[11px] font-medium {{ $q->jawaban_benar == 'B' ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">B. {{ $q->opsi_b }}</p>
                            <p class="text-[11px] font-medium {{ $q->jawaban_benar == 'C' ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">C. {{ $q->opsi_c }}</p>
                            <p class="text-[11px] font-medium {{ $q->jawaban_benar == 'D' ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">D. {{ $q->opsi_d }}</p>
                            <p class="text-[11px] font-medium {{ $q->jawaban_benar == 'E' ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">E. {{ $q->opsi_e }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>
@endsection
