@extends('layouts.app')
@section('title', 'Jadwal Pelajaran')
@section('page_title', 'Jadwal Pelajaran Saya')

@section('content')
<div class="space-y-5">

    @php
        $hariMap = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
        ];
        $todayId   = $hariMap[now()->format('l')] ?? '';
        $siswa     = Auth::guard('siswa')->user();
        $namaKelas = $siswa->kelas->nama ?? '-';
    @endphp

    {{-- Header --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl p-6 text-white flex items-center justify-between">
        <div>
            <p class="text-purple-200 text-xs font-bold uppercase tracking-widest mb-1">Jadwal Mingguan</p>
            <h2 class="text-2xl font-bold">Kelas {{ $namaKelas }}</h2>
            <p class="text-purple-200 text-sm mt-1">
                Hari ini: <span class="text-white font-semibold">{{ $todayId }}</span>,
                {{ now()->translatedFormat('d F Y') }}
            </p>
        </div>
        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-4xl backdrop-blur">📅</div>
    </div>

    @if($data_jadwal->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-16 text-center">
        <div class="text-5xl mb-4">📭</div>
        <h3 class="text-lg font-bold text-slate-700">Belum Ada Jadwal</h3>
        <p class="text-slate-400 text-sm mt-2">Jadwal pelajaran untuk kelas Anda belum tersedia. Silakan hubungi admin sekolah.</p>
    </div>
    @else

    {{-- Tampilan Kartu Per Hari --}}
    <div class="space-y-4">
        @foreach($hari_list as $hari)
        @if(isset($data_jadwal[$hari]))
        @php
            $isToday     = ($hari === $todayId);
            $jadwalHari  = $data_jadwal[$hari];
        @endphp
        <div class="bg-white rounded-2xl border shadow-sm overflow-hidden {{ $isToday ? 'border-purple-300 ring-2 ring-purple-400/30' : 'border-slate-100' }}">

            {{-- Hari Header --}}
            <div class="px-6 py-4 flex items-center justify-between {{ $isToday ? 'bg-gradient-to-r from-purple-600 to-indigo-600' : 'bg-slate-50 border-b border-slate-100' }}">
                <div class="flex items-center gap-3">
                    @if($isToday)
                        <span class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-base">📍</span>
                    @endif
                    <h3 class="font-bold text-base {{ $isToday ? 'text-white' : 'text-slate-800' }}">{{ $hari }}</h3>
                    @if($isToday)
                        <span class="text-xs font-bold bg-white/20 text-white px-3 py-1 rounded-full">Hari Ini</span>
                    @endif
                </div>
                <span class="text-xs font-bold px-3 py-1 rounded-full {{ $isToday ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-500' }}">
                    {{ $jadwalHari->count() }} pelajaran
                </span>
            </div>

            {{-- Jadwal Items --}}
            <div class="divide-y divide-slate-50">
                @foreach($jadwalHari as $jadwal)
                @php
                    $now   = now();
                    $start = \Carbon\Carbon::parse(now()->format('Y-m-d') . ' ' . $jadwal->jam_mulai);
                    $end   = \Carbon\Carbon::parse(now()->format('Y-m-d') . ' ' . $jadwal->jam_selesai);
                    $status = null;
                    if ($isToday) {
                        if ($now->between($start, $end))   $status = 'berlangsung';
                        elseif ($now->lt($start))           $status = 'mendatang';
                        else                                $status = 'selesai';
                    }
                @endphp
                <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50/50 transition group">
                    {{-- Waktu --}}
                    <div class="w-24 shrink-0 text-center">
                        <div class="text-sm font-bold {{ $isToday ? 'text-purple-600' : 'text-slate-600' }}">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5">s/d {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</div>
                    </div>

                    {{-- Divider --}}
                    <div class="w-px h-10 {{ $isToday ? 'bg-purple-200' : 'bg-slate-200' }} shrink-0"></div>

                    {{-- Info Mapel --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-800 truncate">{{ $jadwal->pemetaanAkademik->mataPelajaran->nama ?? '-' }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Guru: {{ $jadwal->pemetaanAkademik->guru->nama ?? '-' }}</p>
                    </div>

                    {{-- Status Badge --}}
                    @if($status === 'berlangsung')
                        <span class="shrink-0 text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg uppercase animate-pulse">🟢 Berlangsung</span>
                    @elseif($status === 'mendatang')
                        <span class="shrink-0 text-[10px] font-bold bg-blue-100 text-blue-600 px-2.5 py-1 rounded-lg uppercase">🔵 Mendatang</span>
                    @elseif($status === 'selesai')
                        <span class="shrink-0 text-[10px] font-bold bg-slate-100 text-slate-400 px-2.5 py-1 rounded-lg uppercase">⚫ Selesai</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>

    {{-- Tabel Mingguan Ringkas --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Ringkasan Tabel Mingguan</h3>
            <p class="text-xs text-slate-400 mt-0.5">Semua mata pelajaran dalam 1 minggu</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left" style="min-width:560px;">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        @foreach($hari_list as $hari)
                        @php $isT = ($hari === $todayId); @endphp
                        <th class="px-4 py-3 text-xs font-bold uppercase tracking-wider {{ $isT ? 'bg-purple-600 text-white' : 'text-slate-500' }}">
                            {{ $hari }}
                            @if($isT)<span class="ml-1 text-[9px] bg-white/20 px-1 rounded-full">★</span>@endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr class="align-top">
                        @foreach($hari_list as $hari)
                        <td class="px-3 py-3 border-r border-slate-50 last:border-r-0">
                            @if(isset($data_jadwal[$hari]))
                                <div class="space-y-1.5">
                                @foreach($data_jadwal[$hari] as $j)
                                <div class="p-2 rounded-lg bg-purple-50 border border-purple-100">
                                    <p class="text-[11px] font-bold text-purple-800 truncate">{{ $j->pemetaanAkademik->mataPelajaran->nama ?? '-' }}</p>
                                    <p class="text-[10px] text-purple-500 mt-0.5">{{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}</p>
                                </div>
                                @endforeach
                                </div>
                            @else
                                <p class="text-[11px] text-slate-300 text-center py-2">–</p>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
