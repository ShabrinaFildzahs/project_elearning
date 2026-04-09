@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Sistem Administrasi Pusat')

@section('content')
<div class="space-y-8">
    
    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-6 rounded-3xl border-l-4 border-blue-500">
            <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-2">Total Siswa</h4>
            <div class="flex items-end justify-between">
                <p class="text-4xl font-extrabold text-slate-800">1.250</p>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md transition hover:scale-105">+12 Baru</span>
            </div>
        </div>
        <div class="glass-card p-6 rounded-3xl border-l-4 border-purple-500">
            <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-2">Total Guru</h4>
            <div class="flex items-end justify-between">
                <p class="text-4xl font-extrabold text-slate-800">85</p>
                <span class="text-[10px] font-bold text-slate-500 bg-slate-50 px-2 py-1 rounded-md transition hover:scale-105">Tetap</span>
            </div>
        </div>
        <div class="glass-card p-6 rounded-3xl border-l-4 border-emerald-500">
            <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-2">Total Kelas</h4>
            <div class="flex items-end justify-between">
                <p class="text-4xl font-extrabold text-slate-800">36</p>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md transition hover:scale-105">12 Per Tingkat</span>
            </div>
        </div>
        <div class="glass-card p-6 rounded-3xl border-l-4 border-orange-500">
            <h4 class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-2">Mata Pelajaran</h4>
            <div class="flex items-end justify-between">
                <p class="text-4xl font-extrabold text-slate-800">24</p>
                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-md transition hover:scale-105">Aktif</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Quick Actions -->
        <div class="glass-card p-8 rounded-3xl">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center">
                <span class="mr-2">⚡</span> Pintasan Cepat
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <button class="flex flex-col items-center justify-center p-6 rounded-2xl bg-blue-50 border border-blue-100 hover:bg-blue-100 transition group">
                    <span class="text-2xl mb-2 group-hover:scale-110 transition">👤</span>
                    <span class="text-xs font-bold text-blue-700">Tambah Guru</span>
                </button>
                <button class="flex flex-col items-center justify-center p-6 rounded-2xl bg-emerald-50 border border-emerald-100 hover:bg-emerald-100 transition group">
                    <span class="text-2xl mb-2 group-hover:scale-110 transition">🎓</span>
                    <span class="text-xs font-bold text-emerald-700">Tambah Siswa</span>
                </button>
                <button class="flex flex-col items-center justify-center p-6 rounded-2xl bg-purple-50 border border-purple-100 hover:bg-purple-100 transition group">
                    <span class="text-2xl mb-2 group-hover:scale-110 transition">📁</span>
                    <span class="text-xs font-bold text-purple-700">Buat Kelas</span>
                </button>
                <button class="flex flex-col items-center justify-center p-6 rounded-2xl bg-orange-50 border border-orange-100 hover:bg-orange-100 transition group">
                    <span class="text-2xl mb-2 group-hover:scale-110 transition">📆</span>
                    <span class="text-xs font-bold text-orange-700">Atur Jadwal</span>
                </button>
            </div>
        </div>

        <!-- Recent Logs / Monitoring -->
        <div class="glass-card rounded-3xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Aktivitas Sistem Terbaru</h3>
                <span class="text-xs text-blue-600 font-bold hover:underline cursor-pointer">Lihat Semua</span>
            </div>
            <div class="p-6 space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-sm">🔒</div>
                    <div>
                        <p class="text-xs text-slate-600"><span class="font-bold text-slate-800">Admin</span> melakukan login dari IP 192.168.1.5</p>
                        <p class="text-[10px] text-slate-400 mt-1">2 menit yang lalu</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-sm">🆕</div>
                    <div>
                        <p class="text-xs text-slate-600"><span class="font-bold text-slate-800">Guru Budi Santoso</span> mengunggah materi baru: "Introduction to HTML"</p>
                        <p class="text-[10px] text-slate-400 mt-1">15 menit yang lalu</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-sm">⚠️</div>
                    <div>
                        <p class="text-xs text-slate-600">Terjadi kesalahan sinkronisasi pada modul <span class="font-bold text-slate-800">Jadwal</span></p>
                        <p class="text-[10px] text-slate-400 mt-1">1 jam yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
