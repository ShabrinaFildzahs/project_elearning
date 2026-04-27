@extends('layouts.app')
@section('title', 'Kelola Jadwal Pelajaran')
@section('page_title', 'Kelola Jadwal Pelajaran')

@section('content')
<div class="space-y-5">

{{-- Alert Success --}}
@if(session('success'))
<div id="alert-ok" class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span>{{ session('success') }}</span>
    <button onclick="document.getElementById('alert-ok').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">✕</button>
</div>
@endif

{{-- Alert Error / Bentrok --}}
@if($errors->has('bentrok'))
<div id="alert-err" class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z"/></svg>
    <span>{{ $errors->first('bentrok') }}</span>
    <button onclick="document.getElementById('alert-err').remove()" class="ml-auto text-red-400 hover:text-red-600">✕</button>
</div>
@endif

{{-- Bulk Input Banner --}}
<div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
        <p class="text-indigo-200 text-[10px] lg:text-xs font-bold uppercase tracking-widest">Fitur Baru</p>
        <h3 class="text-white font-bold text-sm lg:text-base mt-0.5">Input Jadwal Massal</h3>
        <p class="text-indigo-200 text-xs lg:text-sm mt-0.5">Isi jadwal 1 kelas untuk Senin s/d Sabtu sekaligus dalam 1 form</p>
    </div>
    <a href="{{ route('admin.schedules.bulk.form') }}"
        class="w-full md:w-auto shrink-0 flex items-center justify-center gap-2 px-5 py-2.5 bg-white hover:bg-indigo-50 text-indigo-700 font-bold rounded-xl text-sm transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Buka Form Input Massal
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    {{-- ===== PANEL KIRI: FILTER ===== --}}
    <div class="space-y-4">


        {{-- Filter Kelas --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Filter Tampilan</p>
            <form method="GET" action="{{ route('admin.schedules.index') }}">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tampilkan Jadwal Kelas</label>
                <select name="kelas_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-400 cursor-pointer">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($data_kelas as $kelas)
                    <option value="{{ $kelas->id }}" {{ $selected_kelas == $kelas->id ? 'selected' : '' }}>
                        {{ $kelas->nama }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    {{-- ===== PANEL KANAN: TABEL MINGGUAN ===== --}}
    <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
            <div>
                <h3 class="font-bold text-slate-800">
                    Jadwal Mingguan
                    @if($selected_kelas)
                        — <span class="text-blue-600">{{ $data_kelas->firstWhere('id', $selected_kelas)->nama ?? '' }}</span>
                    @else
                        <span class="text-slate-400 font-normal text-sm">— Semua Kelas</span>
                    @endif
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ $data_jadwal->count() }} slot jadwal tercatat</p>
            </div>
            <div class="flex flex-wrap gap-1.5">
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                    @php $cnt = isset($jadwal_by_hari[$h]) ? $jadwal_by_hari[$h]->count() : 0; @endphp
                    @if($cnt > 0)
                    <span class="text-[9px] font-bold bg-blue-50 text-blue-600 border border-blue-100 px-2 py-0.5 rounded-full">
                        {{ substr($h,0,3) }}: {{ $cnt }}
                    </span>
                    @endif
                @endforeach
            </div>
        </div>

        @if($data_jadwal->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4 text-3xl">📭</div>
            <h3 class="font-bold text-slate-700">Belum Ada Jadwal</h3>
            <p class="text-slate-400 text-sm mt-2">Gunakan tombol "Buka Form Input Massal" di atas untuk membuat jadwal.</p>
        </div>
        @else
        {{-- Tabel Mingguan --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left" style="min-width: 620px;">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase tracking-wider bg-slate-50 border-b border-slate-100 w-20">Waktu</th>
                        @foreach($hari_list as $hari)
                        @php
                            $isToday = (\Carbon\Carbon::now()->locale('id')->isoFormat('dddd') === $hari);
                        @endphp
                        <th class="px-3 py-3 text-xs font-bold uppercase tracking-wider border-b border-slate-100 {{ $isToday ? 'bg-blue-600 text-white' : 'bg-slate-50 text-slate-500' }}">
                            {{ $hari }}
                            @if($isToday)<span class="ml-1 text-[9px] bg-white/20 px-1.5 py-0.5 rounded-full">Hari Ini</span>@endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Kumpulkan semua jam unik untuk jadi baris
                        $jamSlots = $data_jadwal->map(fn($j) => substr($j->jam_mulai,0,5))->unique()->sort()->values();
                        $colors = ['blue','emerald','violet','amber','rose','cyan','indigo','orange','teal','pink'];
                        $mapelColors = [];
                        $colorIdx = 0;
                    @endphp

                    @if($jamSlots->isEmpty())
                    <tr><td colspan="7" class="text-center py-10 text-slate-400 text-sm">Tidak ada data jadwal.</td></tr>
                    @else
                    @foreach($jamSlots as $jam)
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                        <td class="px-4 py-3 text-xs font-bold text-slate-500 whitespace-nowrap align-top">{{ $jam }}</td>
                        @foreach($hari_list as $hari)
                        <td class="px-2 py-2 align-top">
                            @php
                                $slots = isset($jadwal_by_hari[$hari])
                                    ? $jadwal_by_hari[$hari]->filter(fn($j) => substr($j->jam_mulai,0,5) === $jam)
                                    : collect();
                            @endphp
                            @forelse($slots as $jadwal)
                            @php
                                $mapelNama = $jadwal->pemetaanAkademik->mataPelajaran->nama ?? '-';
                                if (!isset($mapelColors[$mapelNama])) {
                                    $mapelColors[$mapelNama] = $colors[$colorIdx % count($colors)];
                                    $colorIdx++;
                                }
                                $c = $mapelColors[$mapelNama];
                                $bgMap = [
                                    'blue'=>'bg-blue-50 border-blue-200 text-blue-800',
                                    'emerald'=>'bg-emerald-50 border-emerald-200 text-emerald-800',
                                    'violet'=>'bg-violet-50 border-violet-200 text-violet-800',
                                    'amber'=>'bg-amber-50 border-amber-200 text-amber-800',
                                    'rose'=>'bg-rose-50 border-rose-200 text-rose-800',
                                    'cyan'=>'bg-cyan-50 border-cyan-200 text-cyan-800',
                                    'indigo'=>'bg-indigo-50 border-indigo-200 text-indigo-800',
                                    'orange'=>'bg-orange-50 border-orange-200 text-orange-800',
                                    'teal'=>'bg-teal-50 border-teal-200 text-teal-800',
                                    'pink'=>'bg-pink-50 border-pink-200 text-pink-800',
                                ];
                                $dotMap = [
                                    'blue'=>'bg-blue-500','emerald'=>'bg-emerald-500','violet'=>'bg-violet-500',
                                    'amber'=>'bg-amber-500','rose'=>'bg-rose-500','cyan'=>'bg-cyan-500',
                                    'indigo'=>'bg-indigo-500','orange'=>'bg-orange-500','teal'=>'bg-teal-500','pink'=>'bg-pink-500',
                                ];
                                $cardClass = $bgMap[$c] ?? 'bg-slate-50 border-slate-200 text-slate-800';
                                $dotClass  = $dotMap[$c] ?? 'bg-slate-400';
                            @endphp
                            <div class="rounded-lg border p-2 mb-1 {{ $cardClass }} group relative" style="min-width:90px;">
                                <div class="flex items-center gap-1 mb-0.5">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $dotClass }} shrink-0"></div>
                                    <p class="text-[11px] font-bold truncate leading-tight">{{ $mapelNama }}</p>
                                </div>
                                <p class="text-[10px] opacity-70 leading-tight truncate">{{ $jadwal->pemetaanAkademik->guru->nama ?? '-' }}</p>
                                @if(!$selected_kelas)
                                <p class="text-[10px] opacity-60 leading-tight truncate">{{ $jadwal->pemetaanAkademik->kelas->nama ?? '-' }}</p>
                                @endif
                                <p class="text-[10px] font-semibold opacity-80 mt-0.5">
                                    {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                </p>
                                {{-- Action buttons --}}
                                <div class="absolute top-1 right-1 hidden group-hover:flex gap-0.5">
                                    <form action="{{ route('admin.schedules.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-5 h-5 rounded bg-white/80 hover:bg-red-50 flex items-center justify-center shadow-sm transition">
                                            <svg class="w-2.5 h-2.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="text-slate-100 text-center py-1">–</div>
                            @endforelse
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

</div>

@endsection
