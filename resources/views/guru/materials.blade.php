@extends('layouts.app')
@section('title', 'Upload Materi')
@section('page_title', 'Materi Pelajaran')

@section('content')
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">✓ {{ session('success') }}</div>
@endif
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-bold text-slate-800">Materi yang Diupload <span class="text-slate-400 font-normal">({{ $materials->total() }})</span></h3>
    <a href="{{ route('guru.materials.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
        <span>Upload Materi</span>
    </a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($materials as $material)
    <div class="glass-card rounded-2xl p-5 hover:shadow-lg transition group">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                <a href="{{ route('guru.materials.edit', $material) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('guru.materials.destroy', $material) }}" method="POST" onsubmit="return confirm('Hapus materi?')">
                    @csrf @method('DELETE')
                    <button class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <h4 class="font-bold text-slate-800 text-sm leading-tight">{{ $material->title }}</h4>
        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $material->description ?: 'Tidak ada deskripsi' }}</p>
        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-700">{{ $material->academicMap->subject->name ?? '-' }}</p>
                <p class="text-xs text-slate-400">{{ $material->academicMap->class->name ?? '-' }}</p>
            </div>
            <span class="text-xs text-slate-400">{{ $material->created_at->diffForHumans() }}</span>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-16 text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p>Belum ada materi yang diupload.</p>
    </div>
    @endforelse
</div>
@if($materials->hasPages())
<div class="mt-6">{{ $materials->links() }}</div>
@endif
@endsection
