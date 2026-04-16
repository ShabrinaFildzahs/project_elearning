@extends('layouts.app')

@section('title', 'Tugas & Kuis')
@section('page_title', 'Tugas & Kuis')

@section('content')
<div class="space-y-8">

    {{-- Alert --}}
    @if(session('success'))
        <div id="alert-success" class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('alert-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Stats Row --}}
    @php
        $total = $data_tugas->total();
        $submittedCount = count($idTugasSudahDikirim ?? []);
        $pendingCount = $total - $submittedCount;
        $quizCount = $data_tugas->where('tipe', 'kuis')->count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-300">
                    <svg class="w-6 h-6 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Total</span>
            </div>
            <div class="text-3xl font-black text-slate-800">{{ $total }}</div>
            <p class="text-sm text-slate-500 mt-1 font-medium">Tugas & Kuis</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center group-hover:bg-emerald-600 transition-colors duration-300">
                    <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Selesai</span>
            </div>
            <div class="text-3xl font-black text-emerald-600">{{ $submittedCount }}</div>
            <p class="text-sm text-slate-500 mt-1 font-medium">Sudah Dikirim</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center group-hover:bg-orange-600 transition-colors duration-300">
                    <svg class="w-6 h-6 text-orange-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Pending</span>
            </div>
            <div class="text-3xl font-black text-orange-500">{{ max(0, $pendingCount) }}</div>
            <p class="text-sm text-slate-500 mt-1 font-medium">Belum Dikerjakan</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center group-hover:bg-purple-600 transition-colors duration-300">
                    <svg class="w-6 h-6 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Kuis</span>
            </div>
            <div class="text-3xl font-black text-purple-600">{{ $quizCount }}</div>
            <p class="text-sm text-slate-500 mt-1 font-medium">Kuis Aktif</p>
        </div>
    </div>

    {{-- Main Content --}}
    <div>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-xl font-black text-slate-800">Daftar Tugas & Kuis</h3>
                <p class="text-sm text-slate-500 font-medium">Kelola dan kerjakan tugas akademikmu di sini</p>
            </div>
            <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
                <button class="px-5 py-2 rounded-full text-xs font-bold bg-blue-600 text-white shadow-lg shadow-blue-200 transition-transform active:scale-95">Semua</button>
                <button class="px-5 py-2 rounded-full text-xs font-bold bg-white text-slate-600 border border-slate-100 hover:bg-slate-50 transition-all active:scale-95">Tugas</button>
                <button class="px-5 py-2 rounded-full text-xs font-bold bg-white text-slate-600 border border-slate-100 hover:bg-slate-50 transition-all active:scale-95">Kuis</button>
            </div>
        </div>

        @if($data_tugas->isEmpty())
            <div class="bg-white rounded-3xl border border-slate-100 p-20 text-center shadow-sm">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-5xl">🎉</span>
                </div>
                <h3 class="text-xl font-black text-slate-800 mb-2">Semua Tugas Selesai!</h3>
                <p class="text-slate-500 font-medium max-w-xs mx-auto">Saat ini tidak ada tugas atau kuis yang perlu dikerjakan. Kamu boleh istirahat!</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($data_tugas as $tugas)
                @php
                    $isSubmitted = in_array($tugas->id, $idTugasSudahDikirim ?? []);
                    $isOverdue   = \Carbon\Carbon::parse($tugas->tenggat_waktu)->isPast();
                    $daysLeft    = now()->diffInDays($tugas->tenggat_waktu, false);
                    $accentColor = $tugas->tipe == 'kuis' ? 'purple' : 'blue';
                @endphp
                <div class="group bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    {{-- Card Header --}}
                    <div class="p-6 pb-0 flex items-start justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-{{ $accentColor }}-50 flex items-center justify-center text-{{ $accentColor }}-600 group-hover:bg-{{ $accentColor }}-600 group-hover:text-white transition-colors duration-500">
                            @if($tugas->tipe == 'kuis')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-widest
                            @if($isSubmitted) bg-emerald-100 text-emerald-600
                            @elseif($isOverdue) bg-red-100 text-red-600
                            @else bg-{{ $accentColor }}-100 text-{{ $accentColor }}-600 @endif">
                            @if($isSubmitted) Terkirim
                            @elseif($isOverdue) Terlewat
                            @else Aktif @endif
                        </span>
                    </div>

                    {{-- Card Content --}}
                    <div class="p-6 pt-4 flex-1">
                        <h4 class="text-lg font-black text-slate-800 leading-tight group-hover:text-{{ $accentColor }}-600 transition-colors duration-300">{{ $tugas->judul }}</h4>
                        <p class="text-sm text-slate-500 mt-2 font-medium line-clamp-2">{{ $tugas->deskripsi ?? 'Tidak ada deskripsi tugas.' }}</p>

                        <div class="mt-6 flex flex-wrap gap-2">
                            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 rounded-xl text-[11px] font-bold text-slate-600 border border-slate-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                {{ $tugas->pemetaanAkademik->mataPelajaran->nama ?? '-' }}
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tenggat Waktu</p>
                                <p class="text-sm font-bold @if($isOverdue && !$isSubmitted) text-red-500 @else text-slate-700 @endif mt-0.5">
                                    {{ \Carbon\Carbon::parse($tugas->tenggat_waktu)->translatedFormat('d F Y, H:i') }}
                                </p>
                            </div>
                            @if($daysLeft >= 0 && $daysLeft <= 2 && !$isSubmitted)
                                <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center animate-pulse" title="{{ $daysLeft }} hari lagi!">
                                    <span class="text-orange-600 text-[10px] font-black">!</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Card Footer Actions --}}
                    <div class="p-4 bg-slate-50/50 border-t border-slate-100 mt-auto">
                        @if($isSubmitted)
                            <a href="{{ route('siswa.assignments.show', $tugas->id) }}"
                               class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-white border border-slate-200 text-xs font-black text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 transition-all active:scale-95 duration-200">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                LIHAT DETAIL
                            </a>
                        @elseif($isOverdue)
                            <div class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-red-50 text-xs font-black text-red-500 border border-red-100 opacity-80 decoration-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                DEADLINE TERLEWAT
                            </div>
                        @else
                            <a href="{{ route('siswa.assignments.show', $tugas->id) }}"
                               class="w-full flex items-center justify-center gap-2 py-3 rounded-2xl bg-{{ $accentColor }}-600 text-xs font-black text-white hover:bg-{{ $accentColor }}-700 shadow-lg shadow-{{ $accentColor }}-200 transition-all active:scale-95 duration-200">
                                KERJAKAN SEKARANG
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7-7 7"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if($data_tugas->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $data_tugas->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
