@extends('layouts.app')
@section('title', 'Kelola Jadwal Pelajaran')
@section('page_title', 'Kelola Jadwal Pelajaran')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

{{-- Alert System --}}
@if(session('success'))
<div id="alert-ok" class="animate-in fade-in slide-in-from-top-4 duration-300 flex items-center gap-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-bold shadow-sm shadow-emerald-100/50">
    <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
    </div>
    <span>{{ session('success') }}</span>
    <button onclick="document.getElementById('alert-ok').remove()" class="ml-auto w-8 h-8 rounded-lg hover:bg-emerald-100 flex items-center justify-center transition-colors">
        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
@endif

{{-- Bulk Input Banner --}}
<div class="group relative overflow-hidden rounded-3xl bg-slate-900 p-8 shadow-2xl transition-all duration-500 hover:shadow-indigo-500/10">
    <div class="absolute top-0 right-0 -mr-20 -mt-20 h-64 w-64 rounded-full bg-blue-500/10 blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl"></div>
    
    <div class="relative flex flex-col lg:flex-row lg:items-center justify-between gap-8">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white shadow-xl shadow-indigo-500/30 ring-4 ring-indigo-500/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-1.5">Efisiensi Administrasi</p>
                <h3 class="text-2xl font-black text-white tracking-tight">Sinkronisasi Jadwal Massal</h3>
                <p class="text-slate-400 text-sm mt-1 max-w-lg">Kelola seluruh jadwal mingguan satu kelas secara bersamaan dalam satu form cerdas yang terintegrasi dengan pemetaan guru.</p>
            </div>
        </div>
        <a href="{{ route('admin.schedules.bulk.form') }}"
            class="group/btn relative inline-flex items-center justify-center gap-3 px-8 py-4 bg-white hover:bg-indigo-50 text-slate-900 font-black rounded-2xl text-sm transition-all shadow-xl hover:shadow-white/10 active:scale-95">
            <svg class="w-5 h-5 text-indigo-600 group-hover/btn:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Buka Form Input Massal
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    {{-- Filter Panel --}}
    <div class="space-y-6">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sticky top-24">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                </div>
                <h4 class="font-black text-slate-800 text-sm uppercase tracking-wider">Navigasi Jadwal</h4>
            </div>

            <form method="GET" action="{{ route('admin.schedules.index') }}" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2.5">Pilih Kelas</label>
                    <div class="relative group">
                        <select name="kelas_id" onchange="this.form.submit()"
                            class="w-full pl-4 pr-10 py-3.5 text-sm font-bold rounded-2xl border border-slate-100 bg-slate-50 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition-all cursor-pointer appearance-none">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($data_kelas as $kelas)
                            <option value="{{ $kelas->id }}" {{ $selected_kelas == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-300 group-hover:text-blue-400 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
            </form>

            <div class="mt-8 pt-8 border-t border-slate-50">
                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-4">Statistik Kelas Ini</p>
                @if($selected_kelas)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50 border border-blue-100">
                            <span class="text-xs font-bold text-blue-700">Total Sesi</span>
                            <span class="text-sm font-black text-blue-800">{{ $data_jadwal->count() }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">Pilih kelas untuk melihat detail statistik.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Weekly Grid --}}
    <div class="lg:col-span-3 bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden min-h-[600px]">
        {{-- Header --}}
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">
                    @if($selected_kelas)
                        Jadwal Mingguan <span class="text-blue-600"> Kelas {{ $data_kelas->firstWhere('id', $selected_kelas)->nama ?? '' }}</span>
                    @else
                        Master Jadwal <span class="text-slate-400 font-medium">— Semua Kelas</span>
                    @endif
                </h3>
                <p class="text-sm text-slate-500 mt-1">Menampilkan seluruh alokasi waktu pengajaran</p>
            </div>
            
            <div class="flex flex-wrap gap-2">
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
                    @php 
                        $cnt = isset($jadwal_by_hari[$h]) ? $jadwal_by_hari[$h]->count() : 0;
                        $isT = (\Carbon\Carbon::now()->locale('id')->isoFormat('dddd') === $h);
                    @endphp
                    @if($cnt > 0)
                    <div class="px-2.5 py-1 rounded-lg border {{ $isT ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-slate-200 text-slate-500' }} text-[10px] font-black uppercase tracking-tighter">
                        {{ substr($h,0,3) }}: {{ $cnt }}
                    </div>
                    @endif
                @endforeach
            </div>
        </div>

        @if($data_jadwal->isEmpty())
        <div class="flex flex-col items-center justify-center py-32 text-center">
            <div class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center mb-6 border-4 border-white shadow-inner">
                <span class="text-5xl opacity-40">📅</span>
            </div>
            <h3 class="text-2xl font-black text-slate-800">Data Kosong</h3>
            <p class="text-slate-400 max-w-sm mx-auto mt-2 italic">Belum ada jadwal yang diatur untuk kriteria filter ini.</p>
        </div>
        @else
        <div class="overflow-x-auto no-scrollbar">
            <table class="w-full text-left" style="min-width: 800px;">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] w-24">Waktu</th>
                        @foreach($hari_list as $hari)
                        @php $isToday = (\Carbon\Carbon::now()->locale('id')->isoFormat('dddd') === $hari); @endphp
                        <th class="px-4 py-4 text-xs font-black uppercase tracking-wider text-center border-l border-slate-100 {{ $isToday ? 'bg-blue-600 text-white border-blue-500' : 'text-slate-500' }}">
                            {{ $hari }}
                            @if($isToday)<div class="text-[9px] mt-0.5 opacity-80">Sekarang</div>@endif
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @php
                        $jamSlots = $data_jadwal->map(fn($j) => substr($j->jam_mulai,0,5))->unique()->sort()->values();
                        $colors = ['blue','emerald','violet','amber','rose','cyan','indigo','orange','teal','pink'];
                        $mapelColors = [];
                        $colorIdx = 0;
                    @endphp

                    @foreach($jamSlots as $jam)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-black text-slate-500 align-top group-hover:text-blue-600 transition-colors">{{ $jam }}</td>
                        @foreach($hari_list as $hari)
                        <td class="px-2 py-3 align-top border-l border-slate-100 group/cell">
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
                                    'blue'=>'bg-blue-50 border-blue-100 text-blue-700',
                                    'emerald'=>'bg-emerald-50 border-emerald-100 text-emerald-700',
                                    'violet'=>'bg-violet-50 border-violet-100 text-violet-700',
                                    'amber'=>'bg-amber-50 border-amber-100 text-amber-700',
                                    'rose'=>'bg-rose-50 border-rose-100 text-rose-700',
                                    'cyan'=>'bg-cyan-50 border-cyan-100 text-cyan-700',
                                    'indigo'=>'bg-indigo-50 border-indigo-100 text-indigo-700',
                                    'orange'=>'bg-orange-50 border-orange-100 text-orange-700',
                                    'teal'=>'bg-teal-50 border-teal-100 text-teal-700',
                                    'pink'=>'bg-pink-50 border-pink-100 text-pink-700',
                                ];
                                $dotMap = [
                                    'blue'=>'bg-blue-500','emerald'=>'bg-emerald-500','violet'=>'bg-violet-500',
                                    'amber'=>'bg-amber-500','rose'=>'bg-rose-500','cyan'=>'bg-cyan-500',
                                    'indigo'=>'bg-indigo-500','orange'=>'bg-orange-500','teal'=>'bg-teal-500','pink'=>'bg-pink-500',
                                ];
                                $cardClass = $bgMap[$c] ?? 'bg-slate-50 border-slate-100 text-slate-800';
                                $dotClass  = $dotMap[$c] ?? 'bg-slate-400';
                            @endphp
                            <div class="rounded-xl border p-2.5 mb-1.5 {{ $cardClass }} shadow-sm group/card relative hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <div class="w-1.5 h-1.5 rounded-full {{ $dotClass }} ring-2 ring-white"></div>
                                    <p class="text-[11px] font-black truncate leading-tight uppercase tracking-tight">{{ $mapelNama }}</p>
                                </div>
                                <div class="space-y-0.5">
                                    <p class="text-[10px] font-bold opacity-70 leading-tight truncate">{{ $jadwal->pemetaanAkademik->guru->nama ?? '-' }}</p>
                                    @if(!$selected_kelas)
                                    <p class="text-[9px] font-black opacity-40 uppercase tracking-widest truncate">{{ $jadwal->pemetaanAkademik->kelas->nama ?? '-' }}</p>
                                    @endif
                                </div>
                                <div class="mt-2 pt-2 border-t border-black/5 flex items-center justify-between">
                                    <span class="text-[9px] font-black opacity-60">
                                        {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                    </span>
                                    
                                    {{-- Actions --}}
                                    <div class="hidden group-hover/card:flex items-center gap-1">
                                        <form action="{{ route('admin.schedules.destroy', $jadwal->id) }}" method="POST" onsubmit="return confirm('Hapus slot jadwal ini?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-5 h-5 rounded-lg bg-white/80 hover:bg-red-500 hover:text-white flex items-center justify-center shadow-sm transition-all duration-300">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-slate-100 font-black text-center py-2 group-hover/cell:text-slate-200 transition-colors opacity-20 hover:opacity-100">...</div>
                            @endforelse
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <div class="p-8 bg-slate-50/50 border-t border-slate-100">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-4 text-xs font-bold text-slate-400">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Aktif</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Belum Terisi</span>
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sistem Binaa E-Learning v2.0</p>
            </div>
        </div>
    </div>
</div>

</div>

@endsection
