@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page_title', 'Selamat Datang, ' . (auth()->user()->name ?? 'Guru'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <!-- Left Column: Activity & Classes -->
    <div class="md:col-span-2 space-y-8">
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="glass-card p-6 rounded-2xl">
                <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Total Siswa</h4>
                <p class="text-3xl font-extrabold text-slate-800">120</p>
                <div class="mt-2 text-[10px] text-emerald-600 font-bold">↑ 5% dari bulan lalu</div>
            </div>
            <div class="glass-card p-6 rounded-2xl">
                <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Tugas Masuk</h4>
                <p class="text-3xl font-extrabold text-slate-800">45</p>
                <div class="mt-2 text-[10px] text-orange-500 font-bold">12 Belum Dinilai</div>
            </div>
            <div class="glass-card p-6 rounded-2xl">
                <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Materi Aktif</h4>
                <p class="text-3xl font-extrabold text-slate-800">8</p>
                <div class="mt-2 text-[10px] text-blue-600 font-bold">Di 3 Mata Pelajaran</div>
            </div>
        </div>

        <!-- Schedule -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <h3 class="font-bold text-slate-800">Jadwal Mengajar Hari Ini</h3>
                <button class="text-xs font-bold text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg transition border border-blue-100">Lihat Semua</button>
            </div>
            <div class="p-0">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold">
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Kelas / Mapel</th>
                            <th class="px-6 py-4">Ruang</th>
                            <th class="px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-600">07:30 - 09:00</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-800">X RPL 1</span>
                                <p class="text-xs text-slate-500">Pemrograman Dasar</p>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-600">Lab Komputer 2</td>
                            <td class="px-6 py-4">
                                <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Upload Materi">📑</button>
                                <button class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Presensi">📝</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Submissions & Notifications -->
    <div class="space-y-8">
        <!-- Recent Submissions -->
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <h3 class="font-bold text-slate-800">Pengumpulan Terbaru</h3>
                <span class="text-[10px] font-bold text-orange-600 px-2 py-1 bg-orange-100 rounded-md">12 Baru</span>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex items-center space-x-3 p-3 hover:bg-slate-50 rounded-xl transition cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600">AF</div>
                    <div class="flex-1 overflow-hidden">
                        <h4 class="text-xs font-bold text-slate-800 truncate">Ahmad Fathanah</h4>
                        <p class="text-[10px] text-slate-500 truncate">Tugas Basis Data - X RPL 1</p>
                    </div>
                    <div class="text-[10px] text-slate-400 font-medium">5m ago</div>
                </div>
                <div class="flex items-center space-x-3 p-3 hover:bg-slate-50 rounded-xl transition cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-emerald-600">LR</div>
                    <div class="flex-1 overflow-hidden">
                        <h4 class="text-xs font-bold text-slate-800 truncate">Laras Riani</h4>
                        <p class="text-[10px] text-slate-500 truncate">Quiz Web Dasar - X RPL 2</p>
                    </div>
                    <div class="text-[10px] text-slate-400 font-medium">15m ago</div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <button class="w-full text-xs font-bold text-slate-500 hover:text-blue-600 transition">Lihat Semua Pengumpulan</button>
            </div>
        </div>

        <!-- Forum Activity -->
        <div class="glass-card p-6 rounded-2xl">
            <h3 class="font-bold text-slate-800 mb-4">Forum Diskusi</h3>
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        <span class="font-bold text-slate-800">Andini</span> bertanya di forum <span class="text-blue-600 font-medium">Pemrograman Web</span>: "Pak, ijin tanya untuk tag flexbox..."
                    </p>
                </div>
            </div>
            <button class="w-full mt-6 py-2 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">Buka Forum</button>
        </div>
    </div>

</div>
@endsection
