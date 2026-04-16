@extends('layouts.app')
@section('title', 'Jadwal Mengajar')
@section('page_title', 'Jadwal Mengajar Saya')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($hari_list as $hari)
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between
            @if(now()->isoFormat('dddd') === $hari) bg-blue-50 @endif">
            <h3 class="font-bold text-slate-800">{{ $hari }}</h3>
            @if(isset($data_jadwal[$hari]))
            <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">{{ count($data_jadwal[$hari]) }} kelas</span>
            @endif
        </div>
        <div class="p-4 space-y-3">
            @if(isset($data_jadwal[$hari]) && count($data_jadwal[$hari]) > 0)
                @foreach($data_jadwal[$hari] as $jadwal)
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition">
                    <p class="text-xs font-bold text-blue-600">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $jadwal->pemetaanAkademik->mataPelajaran->nama ?? '-' }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $jadwal->pemetaanAkademik->kelas->nama ?? '-' }}</p>
                </div>
                @endforeach
            @else
                <p class="text-sm text-slate-400 text-center py-4">Tidak ada jadwal</p>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
