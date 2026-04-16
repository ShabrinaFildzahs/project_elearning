@extends('layouts.app')
@section('title', 'Profil Siswa')
@section('page_title', 'Profil Pribadi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="relative mb-8">
        {{-- Header Background --}}
        <div class="h-48 rounded-3xl bg-gradient-to-r from-purple-600 to-indigo-500 shadow-lg overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                </svg>
            </div>
        </div>

        {{-- Profile Card --}}
        <div class="absolute -bottom-12 left-8 flex items-end space-x-6">
            <div class="w-32 h-32 rounded-3xl bg-white p-2 shadow-xl">
                <div class="w-full h-full rounded-2xl bg-purple-100 flex items-center justify-center text-4xl font-bold text-purple-600 border-4 border-purple-50">
                    {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                </div>
            </div>
            <div class="pb-4">
                <h1 class="text-3xl font-extrabold text-white drop-shadow-md">{{ $user->nama_lengkap }}</h1>
                <p class="text-slate-500 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    {{ $user->kelas->nama ?? 'Belum ada kelas' }} · NISN: {{ $user->nisn }}
                </p>
            </div>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-20">
        {{-- Left Column: Account Info --}}
        <div class="space-y-6">
            <div class="glass-card rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-4">Informasi Akun</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email</p>
                            <p class="text-sm font-semibold text-slate-700 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-6 border border-slate-100 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-4">Akademik</h3>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kelas</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $user->kelas->nama ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NISN</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $user->nisn ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tahun Masuk</p>
                            <p class="text-sm font-semibold text-slate-700">{{ $user->tahun_masuk }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Personal Data --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-3xl p-8 border border-slate-100 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    Data Pribadi
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Jenis Kelamin</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">No. Telepon / WhatsApp</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $user->no_hp }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tempat, Tanggal Lahir</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $user->tempat_lahir }}, {{ \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Alamat Lengkap</p>
                        <p class="text-sm font-semibold text-slate-700 leading-relaxed">{{ $user->alamat }}</p>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-slate-100 flex justify-end">
                    <a href="/dashboard" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl transition text-sm">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
