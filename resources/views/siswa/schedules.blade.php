@extends('layouts.app')

@section('title', 'Jadwal Pelajaran')
@section('page_title', 'Jadwal Pelajaran Saya')

@section('content')
<div class="space-y-6">

    @php
        $dayNames = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $today = now()->locale('id')->isoFormat('dddd');
        $dayMap = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
        $todayId = $dayMap[now()->format('l')] ?? '';
    @endphp

    {{-- Header Info --}}
    <div class="glass-card p-6 rounded-2xl flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Jadwal Mingguan</h3>
            <p class="text-sm text-slate-500 mt-1">Hari ini: <span class="font-semibold text-blue-600">{{ $todayId ?: now()->format('l') }}</span></p>
        </div>
        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-3xl">📅</div>
    </div>

    @if($schedules->isEmpty())
        <div class="glass-card p-16 rounded-2xl text-center">
            <div class="text-5xl mb-4">📭</div>
            <h3 class="text-lg font-bold text-slate-700">Belum Ada Jadwal</h3>
            <p class="text-slate-500 text-sm mt-2">Jadwal pelajaran belum tersedia. Hubungi admin sekolah.</p>
        </div>
    @else
        @foreach($dayNames as $day)
            @if(isset($schedules[$day]))
                <div class="glass-card rounded-2xl overflow-hidden {{ $day == $todayId ? 'ring-2 ring-blue-400' : '' }}">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between
                        {{ $day == $todayId ? 'bg-blue-600' : 'bg-slate-50/50' }}">
                        <h3 class="font-bold {{ $day == $todayId ? 'text-white' : 'text-slate-700' }}">{{ $day }}</h3>
                        @if($day == $todayId)
                            <span class="text-xs font-bold bg-white/20 text-white px-3 py-1 rounded-full">📍 Hari Ini</span>
                        @else
                            <span class="text-xs text-slate-400 font-medium">{{ $schedules[$day]->count() }} pelajaran</span>
                        @endif
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($schedules[$day] as $schedule)
                        <div class="px-6 py-4 flex items-center hover:bg-slate-50/50 transition">
                            <div class="w-28 shrink-0">
                                <span class="text-sm font-semibold text-blue-600">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                </span>
                                <span class="text-slate-400 text-xs"> – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                            </div>
                            <div class="flex-1 ml-4">
                                <p class="font-bold text-slate-800">{{ $schedule->academicMap->subject->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Kelas {{ $schedule->academicMap->class->name ?? '-' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-slate-600">{{ $schedule->academicMap->teacher->name ?? '-' }}</p>
                                @php
                                    $now = now();
                                    $start = \Carbon\Carbon::parse(now()->format('Y-m-d') . ' ' . $schedule->start_time);
                                    $end   = \Carbon\Carbon::parse(now()->format('Y-m-d') . ' ' . $schedule->end_time);
                                    $isToday = ($day == $todayId);
                                @endphp
                                @if($isToday && $now->between($start, $end))
                                    <span class="text-[10px] font-bold bg-emerald-100 text-emerald-600 px-2 py-1 rounded-md uppercase">Berlangsung</span>
                                @elseif($isToday && $now->lt($start))
                                    <span class="text-[10px] font-bold bg-blue-100 text-blue-600 px-2 py-1 rounded-md uppercase">Mendatang</span>
                                @elseif($isToday && $now->gt($end))
                                    <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-1 rounded-md uppercase">Selesai</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    @endif

</div>
@endsection
