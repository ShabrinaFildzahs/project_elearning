@extends('layouts.app')

@section('title', 'Forum Diskusi')
@section('page_title', 'Forum Diskusi')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-5 py-4 rounded-xl">✅ {{ session('success') }}</div>
    @endif

    {{-- Create New Discussion --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        <button onclick="document.getElementById('new-forum-form').classList.toggle('hidden')"
                class="w-full px-6 py-5 flex items-center justify-between hover:bg-slate-50/50 transition text-left">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">💬</div>
                <span class="font-bold text-slate-700">Buat Diskusi Baru</span>
            </div>
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </button>
        <div id="new-forum-form" class="hidden border-t border-slate-100 p-6">
            <form action="{{ route('siswa.forums.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Mata Pelajaran / Kelas</label>
                        <select name="academic_map_id" required
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">-- Pilih Kelas & Mapel --</option>
                            @foreach($academicMaps as $map)
                                <option value="{{ $map->id }}">
                                    {{ $map->subject->name ?? '-' }} — Kelas {{ $map->class->name ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Judul Diskusi</label>
                        <input type="text" name="title" required placeholder="Tulis judul diskusi..."
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1.5">Isi Pertanyaan / Diskusi</label>
                    <textarea name="content" required rows="4" placeholder="Tulis pertanyaan atau topik diskusi dengan jelas..."
                              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition">
                        Buat Diskusi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Forum List --}}
    @if($forums->isEmpty())
        <div class="glass-card p-16 rounded-2xl text-center">
            <div class="text-5xl mb-4">💬</div>
            <h3 class="text-lg font-bold text-slate-700">Belum Ada Diskusi</h3>
            <p class="text-slate-500 text-sm mt-2">Jadilah yang pertama membuka diskusi!</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($forums as $forum)
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-md transition-all duration-200">
                <div class="p-5 flex items-start gap-4">
                    <div class="w-10 h-10 shrink-0 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white text-sm">
                        {{ strtoupper(substr($forum->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <a href="{{ route('siswa.forums.show', $forum) }}"
                                   class="font-bold text-slate-800 hover:text-blue-600 transition leading-snug block">
                                    {{ $forum->title }}
                                </a>
                                <div class="flex items-center gap-3 mt-1 flex-wrap">
                                    <span class="text-xs text-slate-500">
                                        oleh <span class="font-semibold">{{ $forum->user->name ?? 'Anonim' }}</span>
                                    </span>
                                    <span class="text-xs px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full font-medium">
                                        {{ $forum->academicMap->subject->name ?? '-' }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $forum->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 flex items-center text-xs text-slate-400 space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <span>{{ $forum->comments_count }}</span>
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 mt-2 line-clamp-2 leading-relaxed">{{ $forum->content }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-center">
            {{ $forums->links() }}
        </div>
    @endif

</div>
@endsection
