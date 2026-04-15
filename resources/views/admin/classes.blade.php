@extends('layouts.app')
@section('title', 'Kelola Kelas & Mapel')
@section('page_title', 'Kelola Kelas & Mata Pelajaran')

@section('content')

{{-- Alert --}}
@if(session('success'))
<div id="alert-success" class="mb-5 flex items-center gap-3 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span>{{ session('success') }}</span>
    <button onclick="document.getElementById('alert-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
@endif

{{-- Main Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5" style="min-width:0">

    {{-- ===== KELAS ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm flex flex-col min-w-0">
        {{-- Header --}}
        <div class="px-4 py-3.5 border-b border-slate-100 flex items-center gap-2.5 bg-gradient-to-r from-blue-50 to-white rounded-t-2xl">
            <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800">Daftar Kelas</p>
                <p class="text-[11px] text-slate-400 truncate">{{ $classes->count() }} kelas terdaftar</p>
            </div>
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full shrink-0">{{ $classes->count() }}</span>
        </div>

        {{-- Add Form --}}
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/60">
            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="class">
                <div class="flex gap-2">
                    <input type="text" name="name" placeholder="Nama kelas baru..." required
                        class="flex-1 min-w-0 px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-400/30 focus:border-blue-400 transition placeholder:text-slate-300">
                    <button type="submit"
                        class="shrink-0 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span class="hidden sm:inline">Tambah</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- List --}}
        <div class="overflow-y-auto flex-1" style="max-height:340px;">
            @forelse($classes as $kelas)
            <div class="group flex items-center justify-between px-4 py-3 border-b border-slate-50 hover:bg-blue-50/40 transition last:border-b-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-7 h-7 rounded-lg bg-blue-100 group-hover:bg-blue-200 transition flex items-center justify-center shrink-0">
                        <span class="text-[9px] font-bold text-blue-700">{{ strtoupper(substr($kelas->name, 0, 2)) }}</span>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 truncate">{{ $kelas->name }}</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                    <span class="text-[11px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full whitespace-nowrap">{{ $kelas->academic_maps_count }} mapel</span>
                    <form action="{{ route('admin.classes.destroy', $kelas->id) }}?type=class" method="POST" onsubmit="return confirm('Hapus kelas {{ $kelas->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-6 h-6 flex items-center justify-center rounded-lg text-slate-200 hover:text-red-500 hover:bg-red-50 transition opacity-0 group-hover:opacity-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16"/></svg>
                </div>
                <p class="text-sm text-slate-400 font-medium">Belum ada kelas</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ===== MATA PELAJARAN ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm flex flex-col min-w-0">
        {{-- Header --}}
        <div class="px-4 py-3.5 border-b border-slate-100 flex items-center gap-2.5 bg-gradient-to-r from-emerald-50 to-white rounded-t-2xl">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800">Mata Pelajaran</p>
                <p class="text-[11px] text-slate-400 truncate">{{ $subjects->count() }} mapel terdaftar</p>
            </div>
            <span class="text-xs font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full shrink-0">{{ $subjects->count() }}</span>
        </div>

        {{-- Add Form --}}
        <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/60">
            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="subject">
                <div class="flex gap-2">
                    <input type="text" name="name" placeholder="Nama mata pelajaran..." required
                        class="flex-1 min-w-0 px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-emerald-400/30 focus:border-emerald-400 transition placeholder:text-slate-300">
                    <button type="submit"
                        class="shrink-0 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span class="hidden sm:inline">Tambah</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- List --}}
        <div class="overflow-y-auto flex-1" style="max-height:340px;">
            @forelse($subjects as $subject)
            <div class="group flex items-center justify-between px-4 py-3 border-b border-slate-50 hover:bg-emerald-50/40 transition last:border-b-0">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-7 h-7 rounded-lg bg-emerald-100 group-hover:bg-emerald-200 transition flex items-center justify-center shrink-0">
                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 truncate">{{ $subject->name }}</span>
                </div>
                <div class="flex items-center gap-1.5 shrink-0 ml-2">
                    <span class="text-[11px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full whitespace-nowrap">{{ $subject->academic_maps_count }} kelas</span>
                    <form action="{{ route('admin.classes.destroy', $subject->id) }}?type=subject" method="POST" onsubmit="return confirm('Hapus mapel {{ $subject->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-6 h-6 flex items-center justify-center rounded-lg text-slate-200 hover:text-red-500 hover:bg-red-50 transition opacity-0 group-hover:opacity-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                </div>
                <p class="text-sm text-slate-400 font-medium">Belum ada mata pelajaran</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ===== PEMETAAN GURU ===== --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm flex flex-col min-w-0">
        {{-- Header --}}
        <div class="px-4 py-3.5 border-b border-slate-100 flex items-center gap-2.5 bg-gradient-to-r from-violet-50 to-white rounded-t-2xl">
            <div class="w-7 h-7 rounded-lg bg-violet-100 flex items-center justify-center shrink-0">
                <svg class="w-3.5 h-3.5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800">Pemetaan Guru</p>
                <p class="text-[11px] text-slate-400 truncate">Assign guru ke kelas & mapel</p>
            </div>
            <span class="text-xs font-bold bg-violet-100 text-violet-700 px-2 py-0.5 rounded-full shrink-0">{{ $academicMaps->count() }}</span>
        </div>

        {{-- Form Pemetaan --}}
        <div class="px-4 py-4 border-b border-slate-100 bg-slate-50/60 space-y-2.5">
            <form action="{{ route('admin.classes.storeMap') ?? '#' }}" method="POST" class="space-y-2.5">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Kelas</label>
                    <select name="class_id" required
                        class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-violet-400/30 focus:border-violet-400 transition text-slate-700 cursor-pointer">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $kelas)<option value="{{ $kelas->id }}">{{ $kelas->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mata Pelajaran</label>
                    <select name="subject_id" required
                        class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-violet-400/30 focus:border-violet-400 transition text-slate-700 cursor-pointer">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Guru Pengampu</label>
                    <select name="teacher_id" required
                        class="w-full px-3 py-2 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-violet-400/30 focus:border-violet-400 transition text-slate-700 cursor-pointer">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $teacher)<option value="{{ $teacher->id }}">{{ $teacher->name }}</option>@endforeach
                    </select>
                </div>
                <button type="submit"
                    class="w-full py-2.5 bg-violet-600 hover:bg-violet-700 active:bg-violet-800 text-white text-sm font-bold rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Simpan Pemetaan
                </button>
            </form>
        </div>

        {{-- Mapping List --}}
        <div class="overflow-y-auto flex-1" style="max-height:220px;">
            @forelse($academicMaps as $map)
            <div class="group flex items-start gap-2.5 px-4 py-3 border-b border-slate-50 hover:bg-violet-50/40 transition last:border-b-0">
                <div class="w-6 h-6 rounded-md bg-violet-100 flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-700 truncate">{{ $map->subject->name ?? '-' }}</p>
                    <p class="text-[11px] text-slate-400 truncate mt-0.5">
                        <span class="font-medium text-slate-500">{{ $map->class->name ?? '-' }}</span>
                        <span class="mx-1">·</span>
                        <span class="text-violet-500 font-medium">{{ $map->teacher->name ?? '-' }}</span>
                    </p>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-8 text-center">
                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center mb-2">
                    <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4"/></svg>
                </div>
                <p class="text-sm text-slate-400 font-medium">Belum ada pemetaan</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
