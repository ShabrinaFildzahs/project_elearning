@extends('layouts.app')
@section('title', 'Tugas & Kuis')
@section('page_title', 'Tugas & Kuis')

@section('content')
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">✓ {{ session('success') }}</div>
@endif
<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-bold text-slate-800">Daftar Tugas <span class="text-slate-400 font-normal">({{ $assignments->total() }})</span></h3>
    <a href="{{ route('guru.assignments.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Buat Tugas</span>
    </a>
</div>

<div class="space-y-4">
    @forelse($assignments as $assignment)
    <div class="glass-card rounded-2xl p-5">
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 {{ $assignment->type === 'kuis' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h4 class="font-bold text-slate-800">{{ $assignment->title }}</h4>
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold uppercase {{ $assignment->type === 'kuis' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">{{ $assignment->type }}</span>
                    </div>
                    <p class="text-xs text-slate-500">{{ $assignment->academicMap->class->name ?? '-' }} · {{ $assignment->academicMap->subject->name ?? '-' }}</p>
                    <p class="text-xs text-slate-400 mt-1">Deadline: <span class="{{ \Carbon\Carbon::parse($assignment->deadline)->isPast() ? 'text-red-500' : 'text-slate-600' }} font-semibold">{{ \Carbon\Carbon::parse($assignment->deadline)->format('d M Y, H:i') }}</span></p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('guru.assignments.submissions', $assignment) }}"
                   class="flex items-center space-x-1.5 px-3 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-lg hover:bg-emerald-100 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ $assignment->submissions_count }} Pengumpulan</span>
                </a>
                <a href="{{ route('guru.assignments.edit', $assignment) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('guru.assignments.destroy', $assignment) }}" method="POST" onsubmit="return confirm('Hapus tugas?')">
                    @csrf @method('DELETE')
                    <button class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-16 text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
        <p>Belum ada tugas yang dibuat.</p>
    </div>
    @endforelse
</div>
@if($assignments->hasPages())
<div class="mt-6">{{ $assignments->links() }}</div>
@endif
@endsection
