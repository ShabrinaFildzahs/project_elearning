@extends('layouts.app')
@section('title', $assignment ? 'Edit Tugas' : 'Buat Tugas')
@section('page_title', $assignment ? 'Edit Tugas / Kuis' : 'Buat Tugas / Kuis Baru')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card rounded-2xl p-8">
        <form action="{{ $assignment ? route('guru.assignments.update', $assignment) : route('guru.assignments.store') }}" method="POST">
            @csrf
            @if($assignment) @method('PUT') @endif

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Kelas / Mata Pelajaran</label>
                    <select name="academic_map_id" required class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Kelas & Mapel --</option>
                        @foreach($academicMaps as $map)
                        <option value="{{ $map->id }}" {{ old('academic_map_id', $assignment?->academic_map_id) == $map->id ? 'selected' : '' }}>
                            {{ $map->class->name ?? '-' }} — {{ $map->subject->name ?? '-' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jenis</label>
                    <select name="type" required class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500">
                        <option value="tugas" {{ old('type', $assignment?->type) == 'tugas' ? 'selected' : '' }}>Tugas (File Upload)</option>
                        <option value="kuis" {{ old('type', $assignment?->type) == 'kuis' ? 'selected' : '' }}>Kuis (Link Eksternal / Ujian Online)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Judul Tugas / Kuis</label>
                    <input type="text" name="title" value="{{ old('title', $assignment?->title) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Deskripsi / Instruksi</label>
                    <textarea name="description" rows="4" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm resize-none">{{ old('description', $assignment?->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Batas Waktu (Deadline)</label>
                    <input type="datetime-local" name="deadline" value="{{ old('deadline', isset($assignment) ? \Carbon\Carbon::parse($assignment->deadline)->format('Y-m-d\TH:i') : '') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm">
                </div>
            </div>

            <div class="flex items-center space-x-3 mt-8">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-200">
                    {{ $assignment ? 'Simpan Perubahan' : 'Buat Tugas' }}
                </button>
                <a href="{{ route('guru.assignments.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold rounded-xl transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
