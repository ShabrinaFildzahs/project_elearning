@extends('layouts.app')

@section('title', 'Materi Pelajaran')
@section('page_title', 'Materi Pelajaran')

@section('content')
<div class="space-y-6">

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-5 py-4 rounded-xl">
            ⚠️ {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-5 py-4 rounded-xl">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Stats bar --}}
    <div class="glass-card p-5 rounded-2xl flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-800">Semua Materi Pelajaran</h3>
            <p class="text-sm text-slate-500 mt-0.5">Total <span class="font-semibold text-blue-600">{{ $data_materi->total() }}</span> materi tersedia</p>
        </div>
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl">📚</div>
    </div>

    @if($data_materi->isEmpty())
        <div class="glass-card p-16 rounded-2xl text-center">
            <div class="text-5xl mb-4">📭</div>
            <h3 class="text-lg font-bold text-slate-700">Belum Ada Materi</h3>
            <p class="text-slate-500 text-sm mt-2">Guru belum mengunggah materi. Pantau terus!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach($data_materi as $materi)
            @php
                $ext = pathinfo($materi->path_file, PATHINFO_EXTENSION);
                $iconMap = ['pdf'=>'📄','doc'=>'📝','docx'=>'📝','xls'=>'📊','xlsx'=>'📊','ppt'=>'📊','pptx'=>'📊','zip'=>'🗜️','mp4'=>'🎬','mp3'=>'🎵'];
                $icon = $iconMap[strtolower($ext)] ?? '📁';
                $colors = ['bg-red-50 text-red-600','bg-blue-50 text-blue-600','bg-emerald-50 text-emerald-600','bg-purple-50 text-purple-600','bg-orange-50 text-orange-600'];
                $colorClass = $colors[$loop->index % count($colors)];
            @endphp
            <div class="glass-card rounded-2xl overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                <div class="p-6">
                    <div class="flex items-start space-x-4 mb-4">
                        <div class="w-12 h-12 rounded-xl {{ $colorClass }} flex items-center justify-center text-2xl shrink-0">
                            {{ $icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-800 leading-snug truncate">{{ $materi->judul }}</h4>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $materi->pemetaanAkademik->mataPelajaran->nama ?? '-' }} — 
                                <span class="font-medium">Kelas {{ $materi->pemetaanAkademik->kelas->nama ?? '-' }}</span>
                            </p>
                        </div>
                    </div>
                    @if($materi->deskripsi)
                        <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 mb-4">{{ $materi->deskripsi }}</p>
                    @endif
                    <div class="flex items-center justify-between text-xs text-slate-400 pt-4 border-t border-slate-100">
                        <span>{{ $materi->created_at->diffForHumans() }}</span>
                        <span class="font-medium">Oleh: {{ $materi->pemetaanAkademik->guru->nama ?? '-' }}</span>
                    </div>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('siswa.materials.download', $materi->id) }}"
                       class="flex items-center justify-center space-x-2 w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        <span>Download Materi</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex justify-center">
            {{ $data_materi->links() }}
        </div>
    @endif

</div>
@endsection
