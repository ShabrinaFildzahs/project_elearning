@extends('layouts.app')
@section('title', 'Kelola Kelas & Mapel')
@section('page_title', 'Kelola Kelas & Mata Pelajaran')

@section('content')
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">✓ {{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Kelas --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-blue-50/50">
            <h3 class="font-bold text-slate-800">Daftar Kelas</h3>
        </div>
        <div class="p-4">
            <form action="{{ route('admin.classes.store') }}" method="POST" class="flex gap-2 mb-4">
                @csrf
                <input type="hidden" name="type" value="class">
                <input type="text" name="name" placeholder="Nama kelas..." required
                    class="flex-1 px-3 py-2 text-sm rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500">
                <button class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition">+ Tambah</button>
            </form>
            <ul class="space-y-2">
                @forelse($classes as $kelas)
                <li class="flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 border border-slate-100">
                    <span class="text-sm font-medium text-slate-700">{{ $kelas->name }}</span>
                    <div class="flex items-center gap-1">
                        <span class="text-xs text-slate-400">{{ $kelas->academic_maps_count }} mapel</span>
                        <form action="{{ route('admin.classes.destroy', $kelas->id) }}?type=class" method="POST" onsubmit="return confirm('Hapus kelas?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                </li>
                @empty
                <li class="text-sm text-slate-400 text-center py-4">Belum ada kelas</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Mata Pelajaran --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-emerald-50/50">
            <h3 class="font-bold text-slate-800">Mata Pelajaran</h3>
        </div>
        <div class="p-4">
            <form action="{{ route('admin.classes.store') }}" method="POST" class="flex gap-2 mb-4">
                @csrf
                <input type="hidden" name="type" value="subject">
                <input type="text" name="name" placeholder="Nama mapel..." required
                    class="flex-1 px-3 py-2 text-sm rounded-lg border border-slate-200 bg-slate-50 focus:outline-none focus:border-emerald-500">
                <button class="px-4 py-2 bg-emerald-600 text-white text-sm font-bold rounded-lg hover:bg-emerald-700 transition">+ Tambah</button>
            </form>
            <ul class="space-y-2">
                @forelse($subjects as $subject)
                <li class="flex items-center justify-between p-3 rounded-lg hover:bg-slate-50 border border-slate-100">
                    <span class="text-sm font-medium text-slate-700">{{ $subject->name }}</span>
                    <div class="flex items-center gap-1">
                        <span class="text-xs text-slate-400">{{ $subject->academic_maps_count }} kelas</span>
                        <form action="{{ route('admin.classes.destroy', $subject->id) }}?type=subject" method="POST" onsubmit="return confirm('Hapus mapel?')">
                            @csrf @method('DELETE')
                            <button class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </form>
                    </div>
                </li>
                @empty
                <li class="text-sm text-slate-400 text-center py-4">Belum ada mata pelajaran</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Pemetaan Guru --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-purple-50/50">
            <h3 class="font-bold text-slate-800">Pemetaan Guru</h3>
        </div>
        <div class="p-4">
            <form action="{{ route('admin.classes.storeMap') ?? '#' }}" method="POST" class="space-y-2 mb-4">
                @csrf
                <select name="class_id" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-slate-50 focus:outline-none">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $kelas)<option value="{{ $kelas->id }}">{{ $kelas->name }}</option>@endforeach
                </select>
                <select name="subject_id" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-slate-50 focus:outline-none">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach
                </select>
                <select name="teacher_id" required class="w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-slate-50 focus:outline-none">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($teachers as $teacher)<option value="{{ $teacher->id }}">{{ $teacher->name }}</option>@endforeach
                </select>
                <button class="w-full py-2 bg-purple-600 text-white text-sm font-bold rounded-lg hover:bg-purple-700 transition">Simpan Pemetaan</button>
            </form>
            <ul class="space-y-2 max-h-64 overflow-y-auto">
                @forelse($academicMaps as $map)
                <li class="p-3 rounded-lg border border-slate-100 text-xs">
                    <p class="font-bold text-slate-700">{{ $map->subject->name ?? '-' }}</p>
                    <p class="text-slate-500">{{ $map->class->name ?? '-' }} · {{ $map->teacher->name ?? '-' }}</p>
                </li>
                @empty
                <li class="text-sm text-slate-400 text-center py-4">Belum ada pemetaan</li>
                @endforelse
            </ul>
        </div>
    </div>

</div>
@endsection
