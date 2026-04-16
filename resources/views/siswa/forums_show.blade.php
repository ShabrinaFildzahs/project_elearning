@extends('layouts.app')

@section('title', $forum->judul)
@section('page_title', 'Forum Diskusi')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <a href="{{ route('siswa.forums.index') }}" class="inline-flex items-center space-x-2 text-sm text-slate-500 hover:text-blue-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        <span>Kembali ke Forum</span>
    </a>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-5 py-4 rounded-xl">✅ {{ session('success') }}</div>
    @endif

    {{-- Forum Thread --}}
    @php
        $namaPembuatOriginal = $forum->pembuat->nama ?? $forum->pembuat->nama_lengkap ?? 'Anonim';
        $tipePembuatOriginal = ($forum->tipe_pembuat === \App\Models\Guru::class) ? 'Guru' : 
                               (($forum->tipe_pembuat === \App\Models\Siswa::class) ? 'Siswa' : 'Admin');
    @endphp
    <div class="glass-card rounded-2xl overflow-hidden shadow-sm border border-slate-100 bg-white">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 shrink-0 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white shadow-sm transition-transform hover:rotate-12">
                    {{ strtoupper(substr($namaPembuatOriginal, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 flex-wrap mb-1">
                        <span class="font-bold text-slate-800">{{ $namaPembuatOriginal }}</span>
                        <span class="text-[10px] px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full font-black uppercase tracking-widest border border-blue-100">
                            {{ $tipePembuatOriginal }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">{{ $forum->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs px-2.5 py-1 bg-slate-50 text-slate-600 rounded-lg font-semibold border border-slate-100">
                             {{ $forum->pemetaanAkademik->mataPelajaran->nama ?? '-' }}
                        </span>
                    </div>
                    <h2 class="text-xl md:text-2xl font-black text-slate-800 mt-4 mb-3 leading-tight">{{ $forum->judul }}</h2>
                    <p class="text-slate-600 text-sm md:text-base leading-relaxed whitespace-pre-line bg-slate-50/30 p-4 rounded-xl">{{ $forum->konten }}</p>
                </div>
            </div>
        </div>
        @if($forum->id_pembuat == auth()->guard('siswa')->id() && $forum->tipe_pembuat === \App\Models\Siswa::class)
        <div class="px-6 pb-4 flex justify-end">
            <form action="{{ route('siswa.forums.destroy', $forum->id) }}" method="POST"
                  onsubmit="return confirm('Hapus diskusi ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-bold transition flex items-center gap-1.5 opacity-60 hover:opacity-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    HAPUS DISKUSI
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Comments --}}
    <div class="glass-card rounded-2xl overflow-hidden shadow-sm border border-slate-100 bg-white">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
            <h3 class="font-bold text-slate-700 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>{{ $forum->komentar->count() }} Balasan</span>
            </h3>
        </div>

        @if($forum->komentar->isEmpty())
            <div class="py-16 text-center">
                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                </div>
                <p class="text-slate-400 text-sm font-medium">Belum ada balasan. Jadilah yang pertama!</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($forum->komentar as $komentar)
                @php
                    $isGuru = $komentar->tipe_pembuat === \App\Models\Guru::class;
                    $isAdmin = $komentar->tipe_pembuat === \App\Models\Admin::class;
                    $namaPembuatKomentar = $komentar->pembuat->nama ?? $komentar->pembuat->nama_lengkap ?? 'Anonim';
                @endphp
                <div class="p-6 flex items-start gap-4 hover:bg-slate-50/30 transition-colors">
                    <div class="w-9 h-9 shrink-0 rounded-full flex items-center justify-center font-bold text-white text-xs shadow-sm
                        @if($isGuru) bg-gradient-to-br from-emerald-500 to-emerald-600
                        @elseif($isAdmin) bg-gradient-to-br from-blue-500 to-blue-600
                        @else bg-gradient-to-br from-purple-500 to-purple-600 @endif">
                        {{ strtoupper(substr($namaPembuatKomentar, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span class="font-bold text-sm text-slate-800">{{ $namaPembuatKomentar }}</span>
                            @if($isGuru || $isAdmin)
                                <span class="text-[9px] font-black px-1.5 py-0.5 bg-emerald-100 text-emerald-700 rounded-md uppercase tracking-widest border border-emerald-200">
                                    {{ $isGuru ? 'Guru' : 'Admin' }}
                                </span>
                            @endif
                            <span class="text-[10px] text-slate-400 font-medium ml-auto">{{ $komentar->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $komentar->konten }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        {{-- Submit Comment --}}
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            <h4 class="font-bold text-xs text-slate-500 mb-4 uppercase tracking-[0.1em] flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Tulis Balasan
            </h4>
            <form action="{{ route('siswa.forums.comments.store', $forum->id) }}" method="POST" class="space-y-4">
                @csrf
                <textarea name="konten" required rows="3"
                          placeholder="Tulis balasan atau pertanyaan lanjutan..."
                          class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition resize-none shadow-sm placeholder:text-slate-300"></textarea>
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-black px-6 py-2.5 rounded-xl transition shadow-lg shadow-blue-100 flex items-center gap-2 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
