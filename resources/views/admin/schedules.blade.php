@extends('layouts.app')
@section('title', 'Jadwal Pelajaran')
@section('page_title', 'Kelola Jadwal Pelajaran')

@section('content')
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">✓ {{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Form Tambah Jadwal --}}
    <div class="glass-card rounded-2xl p-6">
        <h3 class="font-bold text-slate-800 mb-5">Tambah Jadwal</h3>
        <form action="{{ route('admin.schedules.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Kelas / Mapel / Guru</label>
                <select name="id_pemetaan_akademik" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500">
                    <option value="">-- Pilih Pemetaan --</option>
                    @foreach($data_pemetaan as $map)
                    <option value="{{ $map->id }}">
                        {{ $map->kelas->nama ?? '-' }} | {{ $map->mataPelajaran->nama ?? '-' }} | {{ $map->guru->nama ?? '-' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Hari</label>
                <select name="hari" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500">
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                    <option value="{{ $hari }}">{{ $hari }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Mulai</label>
                    <input type="time" name="jam_mulai" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Selesai</label>
                    <input type="time" name="jam_selesai" required class="w-full px-3 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-500">
                </div>
            </div>
            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-200">Simpan Jadwal</button>
        </form>
    </div>

    {{-- Tabel Jadwal --}}
    <div class="lg:col-span-2 glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Semua Jadwal</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Hari</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Guru</th>
                        <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data_jadwal as $jadwal)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">{{ $jadwal->hari }}</span>
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-600 font-medium">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </td>
                        <td class="px-5 py-4 text-sm font-semibold text-slate-800">{{ $jadwal->pemetaanAkademik->kelas->nama ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm text-slate-600">{{ $jadwal->pemetaanAkademik->mataPelajaran->nama ?? '-' }}</td>
                        <td class="px-5 py-4 text-sm text-slate-600">{{ $jadwal->pemetaanAkademik->guru->nama ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <form action="{{ route('admin.schedules.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-12 text-slate-400 text-sm">Belum ada jadwal. Tambahkan jadwal di panel kiri.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
