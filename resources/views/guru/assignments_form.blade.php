@extends('layouts.app')
@section('title', $tugas ? 'Edit Tugas' : 'Buat Tugas')
@section('page_title', $tugas ? 'Edit Tugas / Kuis' : 'Buat Tugas / Kuis Baru')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card rounded-2xl p-8">
        <form action="{{ $tugas ? route('guru.assignments.update', $tugas->id) : route('guru.assignments.store') }}" method="POST">
            @csrf
            @if($tugas) @method('PUT') @endif

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Kelas / Mata Pelajaran</label>
                    <select name="id_pemetaan_akademik" required class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Kelas & Mapel --</option>
                        @foreach($data_pemetaan as $map)
                        <option value="{{ $map->id }}" {{ old('id_pemetaan_akademik', $tugas?->id_pemetaan_akademik) == $map->id ? 'selected' : '' }}>
                            {{ $map->kelas->nama ?? '-' }} — {{ $map->mataPelajaran->nama ?? '-' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jenis</label>
                    <select name="tipe" required class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500">
                        <option value="tugas" {{ old('tipe', $tugas?->tipe) == 'tugas' ? 'selected' : '' }}>Tugas (File Upload)</option>
                        <option value="kuis" {{ old('tipe', $tugas?->tipe) == 'kuis' ? 'selected' : '' }}>Kuis (Link Eksternal / Ujian Online)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Judul Tugas / Kuis</label>
                    <input type="text" name="judul" value="{{ old('judul', $tugas?->judul) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Deskripsi / Instruksi</label>
                    <textarea name="deskripsi" rows="4" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm resize-none">{{ old('deskripsi', $tugas?->deskripsi) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Batas Waktu (Tenggat)</label>
                    <input type="datetime-local" name="tenggat_waktu" value="{{ old('tenggat_waktu', isset($tugas) ? \Carbon\Carbon::parse($tugas->tenggat_waktu)->format('Y-m-d\TH:i') : '') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm">
                </div>
            </div>

            <div class="flex items-center space-x-3 mt-8">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-200">
                    {{ $tugas ? 'Simpan Perubahan' : 'Buat Tugas' }}
                </button>
                <a href="{{ route('guru.assignments.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold rounded-xl transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
