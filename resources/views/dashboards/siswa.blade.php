@extends('layouts.app')

@section('title', 'Dashboard Siswa')
@section('page_title', 'Selamat Datang, ' . (auth()->user()->name ?? 'Siswa'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- Left Column: Status & Schedule -->
    <div class="md:col-span-2 space-y-8">
        <!-- Progress Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="glass-card p-6 rounded-2xl flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 text-xl">
                    📚
                </div>
                <div>
                    <h4 class="text-sm text-slate-500 font-medium">Materi Selesai</h4>
                    <p class="text-2xl font-bold text-slate-800">12 / 15</p>
                </div>
            </div>
            <div class="glass-card p-6 rounded-2xl flex items-center space-x-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 text-xl">
                    ✅
                </div>
                <div>
                    <h4 class="text-sm text-slate-500 font-medium">Tugas Disubmit</h4>
                    <p class="text-2xl font-bold text-slate-800">8 / 10</p>
                </div>
            </div>
        </div>

        <!-- Schedule Today -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Jadwal Pelajaran Hari Ini</h3>
                <span class="text-xs font-semibold px-3 py-1 bg-blue-100 text-blue-600 rounded-full">{{ $today }}, {{ now()->translatedFormat('d F') }}</span>
            </div>
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Waktu</th>
                            <th class="px-6 py-4 font-semibold">Mata Pelajaran</th>
                            <th class="px-6 py-4 font-semibold">Guru</th>
                            <th class="px-6 py-4 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($jadwal_hari_ini as $j)
                        @php
                            $start = \Carbon\Carbon::parse(now()->format('Y-m-d') . ' ' . $j->jam_mulai);
                            $end   = \Carbon\Carbon::parse(now()->format('Y-m-d') . ' ' . $j->jam_selesai);
                            $status = 'Mendatang';
                            $color = 'slate';
                            if (now()->between($start, $end)) {
                                $status = 'Berlangsung';
                                $color = 'emerald';
                            } elseif (now()->gt($end)) {
                                $status = 'Selesai';
                                $color = 'blue';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-slate-600 italic">
                                {{ substr($j->jam_mulai,0,5) }} - {{ substr($j->jam_selesai,0,5) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800">{{ $j->pemetaanAkademik->mataPelajaran->nama ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $j->pemetaanAkademik->guru->nama ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-{{ $color }}-100 text-{{ $color }}-600 text-[10px] font-bold rounded-md uppercase">{{ $status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400 italic text-sm">Tidak ada jadwal pelajaran untuk hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Assignments & Progress -->
    <div class="space-y-8">
        <!-- Progress Detail -->
        <div class="glass-card p-8 rounded-2xl text-center">
            <h3 class="font-bold text-slate-800 mb-6">Progres Belajar Mingguan</h3>
            <div class="relative w-32 h-32 mx-auto mb-6">
                <svg class="w-full h-full" viewBox="0 0 36 36">
                    <path class="text-slate-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    <path class="text-blue-500" stroke-dasharray="75, 100" stroke-width="3" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-2xl font-bold text-slate-800">75%</span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase">Selesai</span>
                </div>
            </div>
            <p class="text-xs text-slate-500 leading-relaxed px-4">Hebat! Kamu telah menyelesaikan sebagian besar materi minggu ini. Selesaikan 2 tugas lagi untuk mencapai target.</p>
        </div>

        <!-- Deadline Aufgaben -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Tugas Mendatang</h3>
            </div>
            <div class="p-4 space-y-3">
                <div class="p-4 rounded-xl bg-red-50 border border-red-100 flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Final Project Web</h4>
                        <p class="text-[10px] text-red-600 font-medium mt-0.5">Deadline: Besok, 23:59</p>
                    </div>
                    <button class="text-xs font-bold text-red-600 hover:underline">Kerjakan</button>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-bold text-slate-800">Quiz Basis Data</h4>
                        <p class="text-[10px] text-slate-500 font-medium mt-0.5">Deadline: 12 April 2024</p>
                    </div>
                    <button class="text-xs font-bold text-blue-600 hover:underline">Lihat</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
