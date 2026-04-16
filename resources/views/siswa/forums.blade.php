@extends('layouts.app')

@section('title', 'Forum Diskusi')
@section('page_title', 'Forum Diskusi')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-5 py-4 rounded-xl">✅ {{ session('success') }}</div>
    @endif    {{-- Empty State --}}
    @if($data_forum->isEmpty())
        <div class="glass-card p-16 rounded-2xl text-center shadow-sm border border-slate-100 bg-white">
            <div class="text-5xl mb-4">💬</div>
            <h3 class="text-lg font-bold text-slate-700">Belum Ada Diskusi</h3>
            <p class="text-slate-500 text-sm mt-2">Daftar diskusi akan muncul jika Guru telah memulai sebuah topik baru.</p>
        </div>  </div>
    @else
        <div class="space-y-3">
            @foreach($data_forum as $forum)
            @php
                $namaPembuat = $forum->pembuat->nama ?? $forum->pembuat->nama_lengkap ?? 'Anonim';
            @endphp
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-md transition-all duration-200 border border-slate-100 bg-white">
                <div class="p-5 flex items-start gap-4">
                    <div class="w-10 h-10 shrink-0 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white text-sm">
                        {{ strtoupper(substr($namaPembuat, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <a href="{{ route('siswa.forums.show', $forum->id) }}"
                                   class="font-bold text-slate-800 hover:text-blue-600 transition leading-snug block truncate">
                                    {{ $forum->judul }}
                                </a>
                                <div class="flex items-center gap-3 mt-1 flex-wrap">
                                    <span class="text-xs text-slate-500">
                                        oleh <span class="font-semibold">{{ $namaPembuat }}</span>
                                    </span>
                                    <span class="text-xs px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full font-medium">
                                        {{ $forum->pemetaanAkademik->mataPelajaran->nama ?? '-' }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $forum->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 flex items-center text-xs text-slate-400 space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span class="font-semibold">{{ $forum->komentar_count }}</span>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2 leading-relaxed">{{ $forum->konten }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-center mt-8">
            {{ $data_forum->links() }}
        </div>
    @endif

</div>
@endsection
