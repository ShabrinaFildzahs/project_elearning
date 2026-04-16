@extends('layouts.app')
@section('title', 'Penilaian Tugas')
@section('page_title', 'Penilaian: ' . $tugas->judul)

@section('content')
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">✓ {{ session('success') }}</div>
@endif

<div class="mb-6 flex items-center justify-between">
    <div>
        <h3 class="font-bold text-slate-800 text-lg">Daftar Pengumpulan</h3>
        <p class="text-sm text-slate-500 mt-1">Kelas: {{ $tugas->pemetaanAkademik->kelas->nama ?? '-' }} | Mapel: {{ $tugas->pemetaanAkademik->mataPelajaran->nama ?? '-' }}</p>
    </div>
    <a href="{{ route('guru.assignments.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-sm font-semibold rounded-xl transition">Kembali</a>
</div>

<div class="glass-card rounded-2xl overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu Kirim</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">File Jawaban</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nilai</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi Penilaian</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($data_pengumpulan as $pengumpulan)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white bg-purple-600">
                            {{ strtoupper(substr($pengumpulan->siswa->nama_lengkap ?? 'S', 0, 1)) }}
                        </div>
                        <span class="font-semibold text-slate-800">{{ $pengumpulan->siswa->nama_lengkap ?? '-' }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm">
                    @if(\Carbon\Carbon::parse($pengumpulan->created_at)->isAfter($tugas->tenggat_waktu))
                        <span class="text-red-600 font-semibold px-2 py-0.5 bg-red-100 rounded-lg">Terlambat: {{ $pengumpulan->created_at->format('d M, H:i') }}</span>
                    @else
                        <span class="text-slate-600">{{ $pengumpulan->created_at->format('d M, H:i') }}</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <a href="{{ asset('storage/' . $pengumpulan->path_file) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Lihat / Download
                    </a>
                </td>
                <td class="px-6 py-4">
                    @if($pengumpulan->nilai !== null)
                        <span class="text-lg font-bold {{ $pengumpulan->nilai < 75 ? 'text-red-500' : 'text-emerald-600' }}">{{ $pengumpulan->nilai }}</span>
                    @else
                        <span class="text-xs font-bold px-2 py-1 bg-amber-100 text-amber-700 rounded-full">Belum Dinilai</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('guru.submissions.grade', $pengumpulan->id) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="number" name="nilai" min="0" max="100" value="{{ $pengumpulan->nilai }}" placeholder="0-100" required
                            class="w-20 px-2 py-1.5 text-sm rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-blue-500 text-center">
                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition">Simpan</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada siswa yang mengumpulkan tugas.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
