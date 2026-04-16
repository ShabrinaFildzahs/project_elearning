@extends('layouts.app')
@section('title', 'Forum Diskusi')
@section('page_title', 'Forum Diskusi')

@section('content')

{{-- Alert --}}
@if(session('success'))
<div id="alert-ok" class="mb-5 flex items-center gap-3 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
    <button onclick="document.getElementById('alert-ok').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">✕</button>
</div>
@endif

{{-- Header Bar --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-slate-800">Diskusi Kelas Saya</h2>
        <p class="text-sm text-slate-400 mt-0.5">Diskusi dari siswa pada kelas yang Anda ampu</p>
    </div>
    <div class="flex items-center gap-2 text-sm text-slate-500 bg-white border border-slate-100 rounded-xl px-4 py-2 shadow-sm">
        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
        <span class="font-semibold text-slate-700">{{ $data_forum->total() }}</span> diskusi aktif
    </div>
</div>

{{-- Forum List --}}
@if($data_forum->isEmpty())
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-16 text-center">
    <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
        </svg>
    </div>
    <h3 class="text-base font-bold text-slate-700 mb-1">Belum Ada Diskusi</h3>
    <p class="text-sm text-slate-400">Belum ada siswa yang membuka diskusi pada kelas yang Anda ampu.</p>
</div>

@else
<div class="space-y-3">
    @foreach($data_forum as $forum)
    @php
        $namaPembuat = $forum->pembuat->nama ?? $forum->pembuat->nama_lengkap ?? 'Anonim';
    @endphp
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-200 overflow-hidden group">
        <div class="p-5 flex items-start gap-4">

            {{-- Avatar --}}
            <div class="w-10 h-10 shrink-0 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center font-bold text-white text-sm shadow-sm">
                {{ strtoupper(substr($namaPembuat, 0, 1)) }}
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('guru.forums.show', $forum->id) }}"
                           class="font-bold text-slate-800 hover:text-emerald-600 transition text-base leading-snug block truncate">
                            {{ $forum->judul }}
                        </a>
                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                            <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $namaPembuat }}
                            </span>
                            <span class="text-slate-200">|</span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                                {{ $forum->pemetaanAkademik->mataPelajaran->nama ?? '-' }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                                {{ $forum->pemetaanAkademik->kelas->nama ?? '-' }}
                            </span>
                        </div>
                    </div>

                    {{-- Right side actions --}}
                    <div class="shrink-0 flex flex-col items-end gap-2">
                        <div class="flex items-center gap-1 text-xs text-slate-400 bg-slate-50 px-2.5 py-1 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            <span class="font-semibold text-slate-600">{{ $forum->komentar_count }}</span>
                        </div>
                        <span class="text-[11px] text-slate-400 whitespace-nowrap">{{ $forum->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                {{-- Preview --}}
                <p class="text-sm text-slate-500 mt-2.5 leading-relaxed line-clamp-2">{{ $forum->konten }}</p>

                {{-- Footer --}}
                <div class="flex items-center gap-3 mt-3 pt-3 border-t border-slate-50">
                    <a href="{{ route('guru.forums.show', $forum->id) }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Balas Diskusi
                    </a>
                    <span class="text-slate-200">·</span>
                    <form action="{{ route('guru.forums.destroy', $forum->id) }}" method="POST"
                          onsubmit="return confirm('Hapus diskusi ini?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-1 text-xs text-slate-400 hover:text-red-500 transition font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($data_forum->hasPages())
<div class="mt-6 flex justify-center">
    {{ $data_forum->links() }}
</div>
@endif

@endif

@endsection
