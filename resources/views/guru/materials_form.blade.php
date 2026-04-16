@extends('layouts.app')
@section('title', $materi ? 'Edit Materi' : 'Upload Materi')
@section('page_title', $materi ? 'Edit Materi' : 'Upload Materi Baru')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card rounded-2xl p-8">
        <form action="{{ $materi ? route('guru.materials.update', $materi->id) : route('guru.materials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($materi) @method('PUT') @endif

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
                        <option value="{{ $map->id }}" {{ old('id_pemetaan_akademik', $materi?->id_pemetaan_akademik) == $map->id ? 'selected' : '' }}>
                            {{ $map->kelas->nama ?? '-' }} — {{ $map->mataPelajaran->nama ?? '-' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Judul Materi</label>
                    <input type="text" name="judul" value="{{ old('judul', $materi?->judul) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Deskripsi <span class="text-slate-400 font-normal normal-case">(opsional)</span></label>
                    <textarea name="deskripsi" rows="3"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm resize-none">{{ old('deskripsi', $materi?->deskripsi) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">
                        File Materi {{ $materi ? '(Kosongkan jika tidak diganti)' : '' }}
                    </label>
                    @if($materi)
                    <p class="text-xs text-slate-500 mb-2">File saat ini: <span class="text-blue-600 font-medium">{{ basename($materi->path_file) }}</span></p>
                    @endif
                    <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition">
                        <svg class="w-8 h-8 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span class="text-sm text-slate-400">Klik untuk memilih file (max 20MB)</span>
                        <input type="file" name="file" class="hidden" {{ $materi ? '' : 'required' }}>
                    </label>
                </div>
            </div>

            <div class="flex items-center space-x-3 mt-8">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-200">
                    {{ $materi ? 'Simpan Perubahan' : 'Upload Materi' }}
                </button>
                <a href="{{ route('guru.materials.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold rounded-xl transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
