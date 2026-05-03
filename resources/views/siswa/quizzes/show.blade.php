@extends('layouts.app')
@section('title', 'Mengerjakan Kuis')
@section('page_title', 'Sesi Kuis Aktif')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 pb-12">
    {{-- Quiz Header --}}
    <div class="glass-card rounded-3xl p-6 md:p-8 border border-slate-100 bg-white shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-inner shrink-0">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">{{ $quiz->judul }}</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $quiz->pemetaanAkademik->mataPelajaran->nama }} · {{ $quiz->pertanyaan->count() }} Soal</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Waktu Pengerjaan</p>
                <p class="text-lg font-black text-purple-600 leading-none mt-1">{{ $quiz->durasi_menit }} Menit</p>
            </div>
            <div class="w-px h-8 bg-slate-100 mx-2"></div>
            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
    </div>

    <form action="{{ route('siswa.quizzes.submit', $quiz->id) }}" method="POST" id="quiz-form" onsubmit="return confirm('Apakah Anda yakin ingin mengumpulkan kuis ini?')">
        @csrf
        <div class="space-y-8">
            @foreach($quiz->pertanyaan as $index => $q)
            <div class="glass-card rounded-3xl p-8 border border-slate-100 bg-white hover:border-purple-200 transition-all shadow-sm">
                <div class="flex items-start gap-6">
                    <span class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-black text-lg shrink-0">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1 space-y-6">
                        <p class="text-lg font-bold text-slate-700 leading-relaxed">{{ $q->pertanyaan }}</p>
                        
                        <div class="grid grid-cols-1 gap-3">
                            @foreach(['A', 'B', 'C', 'D', 'E'] as $opsi)
                            @php $field = 'opsi_' . strtolower($opsi); @endphp
                            <label class="group relative flex items-center p-4 rounded-2xl border border-slate-100 bg-slate-50 cursor-pointer hover:bg-purple-50 hover:border-purple-200 transition-all">
                                <input type="radio" name="jawaban_{{ $q->id }}" value="{{ $opsi }}" required class="hidden peer">
                                <div class="w-6 h-6 rounded-lg border-2 border-slate-200 bg-white flex items-center justify-center transition-all peer-checked:bg-purple-600 peer-checked:border-purple-600 mr-4">
                                    <span class="text-[10px] font-black text-slate-400 peer-checked:text-white transition-all uppercase">{{ $opsi }}</span>
                                </div>
                                <span class="text-sm font-bold text-slate-600 group-hover:text-purple-700 transition-colors">{{ $q->$field }}</span>
                                <div class="absolute right-4 opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="pt-8 flex justify-center">
                <button type="submit" class="group px-12 py-5 bg-purple-600 hover:bg-purple-700 text-white font-black rounded-3xl transition-all shadow-2xl shadow-purple-500/30 hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                    <span>Kirim Jawaban Kuis</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    body {
        background-color: #f8fafc;
    }
</style>
@endsection
