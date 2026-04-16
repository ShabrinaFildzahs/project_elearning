@extends('layouts.app')
@section('title', 'Profil Guru')
@section('page_title', 'Profil Pribadi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="relative mb-8">
        {{-- Header Background --}}
        <div class="h-48 rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-500 shadow-lg overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                </svg>
            </div>
        </div>

        {{-- Profile Card --}}
        <div class="absolute -bottom-12 left-8 flex items-end space-x-6">
            <div class="w-32 h-32 rounded-3xl bg-white p-2 shadow-xl">
                <div class="w-full h-full rounded-2xl bg-emerald-100 flex items-center justify-center text-4xl font-bold text-emerald-600 border-4 border-emerald-50">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
            </div>
            <div class="pb-4">
                <h1 class="text-3xl font-extrabold text-white drop-shadow-md">{{ $user->nama }}</h1>
                <p class="text-slate-500 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    NUPTK/Guru Pengampu
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
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
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
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-4">Pendidikan</h3>
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0 mt-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pendidikan Terakhir</p>
                        <p class="text-sm font-semibold text-slate-700 leading-relaxed">{{ $user->pendidikan_terakhir }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Personal Data --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-3xl p-8 border border-slate-100 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
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
