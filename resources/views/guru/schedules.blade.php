@extends('layouts.app')
@section('title', 'Jadwal Mengajar')
@section('page_title', 'Jadwal Mengajar Saya')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

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
        $guru      = Auth::guard('guru')->user();
        $totalJam  = 0;
        foreach ($data_jadwal as $jadwalHari) {
            foreach ($jadwalHari as $j) {
                $start    = \Carbon\Carbon::parse($j->jam_mulai);
                $end      = \Carbon\Carbon::parse($j->jam_selesai);
                $totalJam += $start->diffInMinutes($end);
            }
        }
        $totalJamStr = floor($totalJam / 60) . ' jam ' . ($totalJam % 60) . ' menit';
    @endphp

    {{-- Header Card --}}
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 h-64 w-64 rounded-full bg-emerald-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 h-64 w-64 rounded-full bg-blue-500/10 blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white shadow-xl shadow-emerald-500/30">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-white tracking-tight">{{ $guru->nama ?? 'Guru' }}</h2>
                    <div class="flex items-center gap-3 mt-1.5 text-slate-400">
                        <span class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Jadwal Aktif
                        </span>
                        <span class="text-slate-600">•</span>
                        <span class="text-sm font-medium">{{ $todayId }}, {{ now()->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-4">
                <div class="px-6 py-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Sesi</p>
                    <p class="text-2xl font-black text-white leading-none">{{ $data_jadwal->flatten()->count() }} <span class="text-sm font-medium text-slate-500">Sesi</span></p>
                </div>
                <div class="px-6 py-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md text-center">
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Durasi Kerja</p>
                    <p class="text-2xl font-black text-white leading-none">{{ floor($totalJam / 60) }}<span class="text-sm font-medium text-slate-500">j</span> {{ $totalJam % 60 }}<span class="text-sm font-medium text-slate-500">m</span></p>
                </div>
            </div>
        </div>
    </div>

    @php $allJadwal = $data_jadwal->flatten(); @endphp

    @if($allJadwal->isEmpty())
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-20 text-center">
        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="text-5xl">📅</span>
        </div>
        <h3 class="text-2xl font-black text-slate-800">Belum Ada Jadwal</h3>
        <p class="text-slate-400 max-w-md mx-auto mt-3">Jadwal mengajar Anda belum dikonfigurasi oleh admin. Mohon hubungi bagian kurikulum untuk informasi lebih lanjut.</p>
    </div>
    @else

    {{-- Detailed Schedule by Day --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- List Column --}}
        <div class="xl:col-span-2 space-y-6">
            @foreach($hari_list as $hari)
            @if(isset($data_jadwal[$hari]))
            @php
                $isToday    = ($hari === $todayId);
                $jadwalHari = $data_jadwal[$hari];
            @endphp
            <div class="bg-white rounded-3xl border {{ $isToday ? 'border-emerald-200 ring-4 ring-emerald-50' : 'border-slate-100' }} shadow-sm overflow-hidden transition-all duration-300">
                
                {{-- Day Header --}}
                <div class="px-6 py-5 flex items-center justify-between {{ $isToday ? 'bg-gradient-to-r from-emerald-500 to-teal-600' : 'bg-slate-50/50 border-b border-slate-100' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl {{ $isToday ? 'bg-white/20 text-white' : 'bg-white border border-slate-200 text-slate-400' }} flex items-center justify-center font-bold shadow-sm">
                            {{ substr($hari,0,1) }}
                        </div>
                        <div>
                            <h3 class="font-black text-lg {{ $isToday ? 'text-white' : 'text-slate-800' }}">{{ $hari }}</h3>
                            @if($isToday)
                                <span class="text-[10px] font-black bg-white/20 text-white px-2 py-0.5 rounded-lg uppercase tracking-widest">Hari Ini</span>
                            @endif
                        </div>
                    </div>
                    <span class="text-xs font-black px-3 py-1.5 rounded-xl {{ $isToday ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-600' }}">
                        {{ $jadwalHari->count() }} Kelas
                    </span>
                </div>

                {{-- Session Items --}}
                <div class="divide-y divide-slate-100">
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
                        $durasi = $start->diffInMinutes($end);
                    @endphp
                    <div class="group relative px-6 py-5 flex items-center gap-6 hover:bg-slate-50 transition-all">
                        @if($status === 'berlangsung')
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
                        @endif

                        {{-- Time Column --}}
                        <div class="w-20 shrink-0 text-right">
                            <div class="text-base font-black {{ $status === 'berlangsung' ? 'text-emerald-600' : 'text-slate-800' }}">
                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            </div>
                            <div class="text-[11px] font-bold text-slate-400 mt-0.5">s/d {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</div>
                        </div>

                        {{-- Vertical Connector --}}
                        <div class="relative py-2 shrink-0">
                            <div class="w-1.5 h-1.5 rounded-full {{ $status === 'berlangsung' ? 'bg-emerald-500 scale-125' : ($status === 'selesai' ? 'bg-slate-300' : 'bg-blue-400') }} shadow-sm"></div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <h4 class="font-black text-slate-800 text-base truncate group-hover:text-blue-600 transition-colors">{{ $jadwal->pemetaanAkademik->mataPelajaran->nama ?? '-' }}</h4>
                            <div class="flex items-center gap-3 mt-1.5">
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-black bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Kelas {{ $jadwal->pemetaanAkademik->kelas->nama ?? '-' }}
                                </span>
                                <span class="text-xs font-medium text-slate-400">{{ $durasi }} Menit</span>
                            </div>
                        </div>

                        {{-- Status Indicator --}}
                        <div class="shrink-0">
                            @if($status === 'berlangsung')
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-xl">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Berlangsung</span>
                                </div>
                            @elseif($status === 'mendatang')
                                <div class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-xl border border-blue-100">
                                    <span class="text-[10px] font-black uppercase tracking-widest">Mendatang</span>
                                </div>
                            @elseif($status === 'selesai')
                                <div class="px-3 py-1.5 bg-slate-100 text-slate-400 rounded-xl">
                                    <span class="text-[10px] font-black uppercase tracking-widest">Selesai</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>

        {{-- Summary Sidebar Column --}}
        <div class="space-y-6">
            <div class="sticky top-24">
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 bg-slate-50 border-b border-slate-100">
                        <h3 class="font-black text-slate-800">Kalender Mingguan</h3>
                        <p class="text-xs text-slate-500 mt-1">Ringkasan jadwal seluruh hari</p>
                    </div>
                    <div class="p-4 space-y-3">
                        @foreach($hari_list as $hari)
                            @php 
                                $cnt = isset($data_jadwal[$hari]) ? $data_jadwal[$hari]->count() : 0;
                                $isT = ($hari === $todayId);
                            @endphp
                            <div class="flex items-center justify-between p-4 rounded-2xl {{ $isT ? 'bg-emerald-50 border border-emerald-100' : 'bg-white border border-slate-50 hover:border-slate-200' }} transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg {{ $isT ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-[10px] font-black">
                                        {{ substr($hari,0,3) }}
                                    </div>
                                    <span class="text-sm font-bold {{ $isT ? 'text-emerald-700' : 'text-slate-700' }}">{{ $hari }}</span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-xs font-black {{ $cnt > 0 ? ($isT ? 'text-emerald-600' : 'text-blue-600') : 'text-slate-300' }}">
                                        {{ $cnt }} <span class="text-[10px] opacity-60">Sesi</span>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="p-6 border-t border-slate-100 bg-slate-50/30">
                        <button onclick="window.print()" class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4h10z"/></svg>
                            Cetak Jadwal Saya
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
    @media print {
        .sidebar, header, button { display: none !important; }
        main { padding: 0 !important; }
        .bg-slate-50 { background-color: white !important; }
        .rounded-3xl { border-radius: 0 !important; }
        .shadow-sm, .shadow-xl, .shadow-2xl { box-shadow: none !important; }
    }
</style>
@endsection
