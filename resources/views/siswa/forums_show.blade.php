@extends('layouts.app')

@section('title', $forum->title)
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
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 shrink-0 rounded-full bg-blue-600 flex items-center justify-center font-bold text-white">
                    {{ strtoupper(substr($forum->user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 flex-wrap mb-1">
                        <span class="font-bold text-slate-800">{{ $forum->user->name ?? 'Anonim' }}</span>
                        <span class="text-xs px-2 py-0.5 bg-blue-50 text-blue-600 rounded-full font-medium capitalize">
                            {{ $forum->user->role ?? '-' }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $forum->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full">
                            {{ $forum->academicMap->subject->name ?? '-' }} — {{ $forum->academicMap->class->name ?? '-' }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 mt-3 mb-3">{{ $forum->title }}</h2>
                    <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $forum->content }}</p>
                </div>
            </div>
        </div>
        @if($forum->user_id == auth()->id())
        <div class="px-6 pb-4 flex justify-end">
            <form action="{{ route('siswa.forums.destroy', $forum) }}" method="POST"
                  onsubmit="return confirm('Hapus diskusi ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium transition">
                    🗑️ Hapus Diskusi
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Comments --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">
                💬 {{ $forum->comments->count() }} Balasan
            </h3>
        </div>

        @if($forum->comments->isEmpty())
            <div class="py-12 text-center">
                <p class="text-slate-400 text-sm">Belum ada balasan. Jadilah yang pertama!</p>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($forum->comments as $comment)
                <div class="p-5 flex items-start gap-3">
                    <div class="w-9 h-9 shrink-0 rounded-full flex items-center justify-center font-bold text-white text-sm
                        @if($comment->user->role == 'guru') bg-emerald-600
                        @elseif($comment->user->role == 'admin') bg-blue-600
                        @else bg-purple-600 @endif">
                        {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 flex-wrap mb-2">
                            <span class="font-bold text-sm text-slate-800">{{ $comment->user->name ?? 'Anonim' }}</span>
                            @if(in_array($comment->user->role ?? '', ['guru','admin']))
                                <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-100 text-emerald-600 rounded-full uppercase">
                                    {{ $comment->user->role }}
                                </span>
                            @endif
                            <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $comment->content }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        {{-- Submit Comment --}}
        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
            <h4 class="font-bold text-sm text-slate-700 mb-3">Tulis Balasan</h4>
            <form action="{{ route('siswa.forums.comments.store', $forum) }}" method="POST" class="space-y-3">
                @csrf
                <textarea name="content" required rows="3"
                          placeholder="Tulis balasan atau pertanyaan lanjutan..."
                          class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none"></textarea>
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition">
                        Kirim Balasan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
