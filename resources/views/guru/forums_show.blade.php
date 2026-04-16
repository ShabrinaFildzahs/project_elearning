@extends('layouts.app')
@section('title', $forum->judul)
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

{{-- Back Button --}}
<div class="mb-5">
    <a href="{{ route('guru.forums.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-emerald-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Daftar Diskusi
    </a>
</div>

<div class="max-w-3xl space-y-5">

    {{-- ===== ORIGINAL POST ===== --}}
    @php
        $namaPembuatOriginal = $forum->pembuat->nama ?? $forum->pembuat->nama_lengkap ?? 'Anonim';
    @endphp
    <div class="bg-white rounded-2xl border border-emerald-100 shadow-sm overflow-hidden">
        {{-- Thread Header --}}
        <div class="px-6 py-4 bg-gradient-to-r from-emerald-50 to-white border-b border-emerald-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center font-bold text-white text-sm shrink-0">
                {{ strtoupper(substr($namaPembuatOriginal, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800">{{ $namaPembuatOriginal }}</p>
                <div class="flex items-center gap-2 flex-wrap mt-0.5">
                    <span class="text-[11px] text-emerald-600 font-medium bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                        {{ $forum->pemetaanAkademik->mataPelajaran->nama ?? '-' }}
                    </span>
                    <span class="text-[11px] text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full">
                        Kelas {{ $forum->pemetaanAkademik->kelas->nama ?? '-' }}
                    </span>
                    <span class="text-[11px] text-slate-400">· {{ $forum->created_at->format('d M Y, H:i') }}</span>
                </div>
            </div>
            <span class="text-xs text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-100 shrink-0">
                {{ $forum->komentar->count() }} balasan
            </span>
        </div>

        {{-- Thread Body --}}
        <div class="px-6 py-5">
            <h1 class="text-lg font-bold text-slate-800 mb-3 leading-snug">{{ $forum->judul }}</h1>
            <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap">{{ $forum->konten }}</p>
        </div>
    </div>

    {{-- ===== REPLIES ===== --}}
    @if($forum->komentar->count() > 0)
    <div>
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3 px-1">
            {{ $forum->komentar->count() }} Balasan
        </h3>
        <div class="space-y-3">
            @foreach($forum->komentar as $komentar)
            @php 
                $isGuru = $komentar->tipe_pembuat === \App\Models\Guru::class;
                $namaPembuatKomentar = $komentar->pembuat->nama ?? $komentar->pembuat->nama_lengkap ?? 'Anonim';
            @endphp
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-start gap-3
                        {{ $isGuru ? 'border-l-[3px] border-l-emerald-400' : '' }}">
                <div class="w-8 h-8 shrink-0 rounded-full flex items-center justify-center font-bold text-xs text-white
                             {{ $isGuru ? 'bg-gradient-to-br from-emerald-400 to-emerald-600' : 'bg-gradient-to-br from-slate-400 to-slate-500' }}">
                    {{ strtoupper(substr($namaPembuatKomentar, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                        <span class="text-sm font-bold text-slate-800">{{ $namaPembuatKomentar }}</span>
                        @if($isGuru)
                        <span class="text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full uppercase tracking-wide">Guru</span>
                        @endif
                        <span class="text-[11px] text-slate-400 ml-auto">{{ $komentar->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap">{{ $komentar->konten }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8 text-center">
        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
            <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <p class="text-sm font-semibold text-slate-600">Belum ada balasan</p>
        <p class="text-xs text-slate-400 mt-1">Jadilah yang pertama membalas diskusi ini</p>
    </div>
    @endif

    {{-- ===== REPLY FORM ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
            </div>
            <span class="text-sm font-bold text-slate-700">Tulis Balasan</span>
            <span class="text-xs text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-full ml-auto">Sebagai Guru</span>
        </div>
        <div class="px-6 py-5">
            <form action="{{ route('guru.forums.comments.store', $forum->id) }}" method="POST" class="space-y-4">
                @csrf
                <textarea name="konten" rows="4" required
                          placeholder="Tulis balasan atau penjelasan untuk diskusi ini..."
                          class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400/30 focus:border-emerald-400 transition resize-none placeholder:text-slate-300 leading-relaxed"></textarea>
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-bold rounded-xl transition shadow-sm shadow-emerald-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
