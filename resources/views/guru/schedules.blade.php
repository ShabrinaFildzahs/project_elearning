@extends('layouts.app')
@section('title', 'Jadwal Mengajar')
@section('page_title', 'Jadwal Mengajar Saya')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
    @foreach($days as $day)
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between
            @if(now()->isoFormat('dddd') === $day) bg-blue-50 @endif">
            <h3 class="font-bold text-slate-800">{{ $day }}</h3>
            @if(isset($schedules[$day]))
            <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-full">{{ count($schedules[$day]) }} kelas</span>
            @endif
        </div>
        <div class="p-4 space-y-3">
            @if(isset($schedules[$day]) && count($schedules[$day]) > 0)
                @foreach($schedules[$day] as $s)
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition">
                    <p class="text-xs font-bold text-blue-600">{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</p>
                    <p class="text-sm font-semibold text-slate-800 mt-1">{{ $s->academicMap->subject->name ?? '-' }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $s->academicMap->class->name ?? '-' }}</p>
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
