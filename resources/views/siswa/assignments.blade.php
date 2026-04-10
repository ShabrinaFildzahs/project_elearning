@extends('layouts.app')

@section('title', 'Tugas & Kuis')
@section('page_title', 'Tugas & Kuis')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-5 py-4 rounded-xl flex items-center space-x-2">
            <span>✅</span><span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $total = $assignments->total();
            $submitted = count($mySubmissions ?? []);
            $pending = $total - $submitted;
        @endphp
        <div class="glass-card p-5 rounded-2xl text-center">
            <div class="text-2xl font-bold text-slate-800">{{ $total }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Total Tugas</div>
        </div>
        <div class="glass-card p-5 rounded-2xl text-center">
            <div class="text-2xl font-bold text-emerald-600">{{ $submitted }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Sudah Dikumpul</div>
        </div>
        <div class="glass-card p-5 rounded-2xl text-center">
            <div class="text-2xl font-bold text-orange-500">{{ $pending }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Belum Dikumpul</div>
        </div>
        <div class="glass-card p-5 rounded-2xl text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $assignments->where('type','kuis')->count() }}</div>
            <div class="text-xs text-slate-500 font-medium mt-1">Kuis</div>
        </div>
    </div>

    @if($assignments->isEmpty())
        <div class="glass-card p-16 rounded-2xl text-center">
            <div class="text-5xl mb-4">🎉</div>
            <h3 class="text-lg font-bold text-slate-700">Tidak Ada Tugas</h3>
            <p class="text-slate-500 text-sm mt-2">Selamat! Saat ini tidak ada tugas yang perlu dikerjakan.</p>
        </div>
    @else
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Daftar Tugas & Kuis</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($assignments as $assignment)
                @php
                    $isSubmitted = in_array($assignment->id, $mySubmissions ?? []);
                    $isOverdue   = $assignment->deadline < now();
                    $daysLeft    = now()->diffInDays($assignment->deadline, false);
                @endphp
                <div class="px-6 py-5 hover:bg-slate-50/50 transition flex items-center justify-between gap-4">
                    <div class="flex items-center space-x-4 min-w-0">
                        <div class="w-10 h-10 shrink-0 rounded-xl flex items-center justify-center
                            @if($assignment->type == 'kuis') bg-purple-100 text-purple-600
                            @else bg-blue-100 text-blue-600 @endif text-lg">
                            {{ $assignment->type == 'kuis' ? '📝' : '📋' }}
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-bold text-slate-800 truncate">{{ $assignment->title }}</h4>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase
                                    @if($assignment->type == 'kuis') bg-purple-100 text-purple-600
                                    @else bg-blue-100 text-blue-600 @endif">
                                    {{ $assignment->type }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $assignment->academicMap->subject->name ?? '-' }} — 
                                Kelas {{ $assignment->academicMap->class->name ?? '-' }}
                            </p>
                            <p class="text-xs mt-1
                                @if($isOverdue && !$isSubmitted) text-red-500 font-semibold
                                @elseif($daysLeft <= 2 && !$isSubmitted) text-orange-500 font-semibold
                                @else text-slate-400 @endif">
                                ⏰ Deadline: {{ $assignment->deadline->format('d M Y, H:i') }}
                                @if(!$isSubmitted)
                                    @if($isOverdue) (Terlambat!)
                                    @elseif($daysLeft <= 2) ({{ $daysLeft }} hari lagi!)
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        @if($isSubmitted)
                            <a href="{{ route('siswa.assignments.show', $assignment) }}"
                               class="flex items-center space-x-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl hover:bg-emerald-100 transition">
                                <span>✅</span><span>Sudah Dikumpul</span>
                            </a>
                        @elseif($isOverdue)
                            <span class="text-xs font-bold text-red-500 bg-red-50 px-4 py-2 rounded-xl">Terlambat</span>
                        @else
                            <a href="{{ route('siswa.assignments.show', $assignment) }}"
                               class="flex items-center space-x-1.5 text-xs font-bold text-white bg-blue-600 px-4 py-2 rounded-xl hover:bg-blue-700 transition">
                                <span>Kerjakan</span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-center">
            {{ $assignments->links() }}
        </div>
    @endif

</div>
@endsection
