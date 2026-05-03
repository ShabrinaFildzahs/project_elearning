@extends('layouts.app')
@section('title', 'Input Jadwal Massal')
@section('page_title', 'Input Jadwal Massal')

@section('content')
<div class="space-y-5">

@php
    $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $selectedKelasId = old('id_kelas', $selected_kelas);
    
    // Kelompokkan pemetaan per kelas untuk JS
    $pemetaanByKelas = [];
    foreach ($data_pemetaan as $p) {
        $pemetaanByKelas[$p->id_kelas][] = [
            'id'    => $p->id,
            'label' => ($p->mataPelajaran->nama ?? '-') . ' - ' . ($p->guru->nama ?? '-'),
        ];
    }
@endphp

{{-- Alert Error --}}
@if($errors->has('bulk'))
<div id="alert-err" class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm animate-in fade-in slide-in-from-top-4 duration-300">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z"/></svg>
    <div class="flex-1">
        <p class="font-bold mb-1">Terdapat Kesalahan:</p>
        @foreach(explode("\n", $errors->first('bulk')) as $err)
            <p class="text-sm">{{ $err }}</p>
        @endforeach
    </div>
    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600">✕</button>
</div>
@endif

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <p class="text-[10px] lg:text-xs text-slate-400 font-medium uppercase tracking-widest">Sistem Sinkronisasi Jadwal</p>
        <h2 class="text-xl font-bold text-slate-800 mt-0.5">Atur Jadwal Mingguan</h2>
    </div>
    <a href="{{ route('admin.schedules.index', ['kelas_id' => $selectedKelasId]) }}"
        class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Jadwal
    </a>
</div>

<form action="{{ route('admin.schedules.bulk') }}" method="POST" id="form-bulk">
@csrf

{{-- Step 1: Pilih Kelas --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-5 relative overflow-hidden group">
    <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/50 rounded-full -mr-16 -mt-16 group-hover:scale-110 transition-transform duration-500"></div>
    <div class="flex items-center gap-3 mb-4 relative">
        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div>
            <p class="font-bold text-slate-800">Langkah 1: Pilih Kelas</p>
            <p class="text-xs text-slate-400 font-medium">Langkah pertama untuk mengelola jadwal mingguan</p>
        </div>
    </div>
    <div class="max-w-xs relative">
        <select name="id_kelas" id="sel-kelas" required
            onchange="window.location.href = '{{ route('admin.schedules.bulk.form') }}?kelas_id=' + this.value"
            class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition font-bold text-slate-700 appearance-none">
            <option value="">-- Pilih Kelas --</option>
            @foreach($data_kelas as $kelas)
            <option value="{{ $kelas->id }}" {{ $selectedKelasId == $kelas->id ? 'selected' : '' }}>
                {{ $kelas->nama }}
            </option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </div>
</div>

{{-- Step 2: Tab Hari --}}
<div class="relative">
    {{-- Overlay jika belum pilih kelas --}}
    @if(!$selectedKelasId)
    <div class="absolute inset-0 z-20 bg-white/60 backdrop-blur-[2px] rounded-3xl flex flex-col items-center justify-center text-center p-10 border-2 border-dashed border-slate-200">
        <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-4 shadow-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Pilih Kelas Terlebih Dahulu</h3>
        <p class="text-sm text-slate-500 max-w-xs mt-2">Anda harus memilih kelas pada Langkah 1 untuk dapat mengisi jadwal dan melihat daftar pemetaan guru.</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 mb-5">
        
        {{-- Sidebar Pemetaan (Reference) --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-5 sticky top-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 text-violet-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-sm leading-none">Daftar Pemetaan</p>
                        <p class="text-[10px] text-slate-400 mt-1 uppercase tracking-widest font-black">Kelas {{ $selectedKelasId ? $data_kelas->firstWhere('id', $selectedKelasId)->nama : '-' }}</p>
                    </div>
                </div>

                <div class="space-y-2 max-h-[400px] overflow-y-auto no-scrollbar">
                    @if($selectedKelasId && isset($pemetaanByKelas[$selectedKelasId]))
                        @foreach($pemetaanByKelas[$selectedKelasId] as $p)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 hover:border-violet-200 transition-colors group">
                            <p class="text-xs font-bold text-slate-700 truncate">{{ explode(' - ', $p['label'])[0] }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5 truncate">{{ explode(' - ', $p['label'])[1] }}</p>
                            <button type="button" onclick="addMappingToCurrentDay('{{ $p['id'] }}', '{{ $p['label'] }}')"
                                class="mt-2 w-full py-1.5 bg-white hover:bg-violet-600 hover:text-white text-violet-600 text-[10px] font-bold rounded-lg border border-violet-100 transition-all opacity-0 group-hover:opacity-100">
                                + Tambah ke Jadwal
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="py-10 text-center">
                            @if($selectedKelasId)
                                <p class="text-xs text-red-400 font-bold px-4 leading-relaxed italic">Belum ada mapping mapel & guru untuk kelas ini</p>
                            @else
                                <p class="text-xs text-slate-300 font-medium italic">Pilih kelas untuk melihat mapping</p>
                            @endif
                        </div>
                    @endif
                </div>

                @if($selectedKelasId)
                <button type="button" onclick="autoFillFromMapping()"
                    class="mt-4 w-full py-3 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-2xl transition shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Isi Otomatis Semua Mapel
                </button>
                @endif
            </div>
        </div>

        {{-- Main Schedule Form --}}
        <div class="lg:col-span-3 bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
            <div class="px-6 pt-6 pb-0 border-b border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">Langkah 2: Isi Jadwal Per Hari</p>
                        <p class="text-xs text-slate-400 font-medium">Klik tab hari lalu sesuaikan jam dan mata pelajaran</p>
                    </div>
                </div>

                {{-- Tab Buttons --}}
                <div class="flex gap-1 -mb-px overflow-x-auto no-scrollbar scroll-smooth">
                    @foreach($hariList as $i => $hari)
                    <button type="button" onclick="switchTab('{{ $hari }}')"
                        id="tab-btn-{{ $hari }}"
                        class="tab-btn whitespace-nowrap px-6 py-3.5 text-sm font-bold rounded-t-2xl border border-b-0 transition-all relative
                            {{ $i === 0 ? 'bg-white border-slate-200 text-blue-600' : 'bg-transparent border-transparent text-slate-400 hover:text-slate-600 hover:bg-white/50' }}">
                        {{ $hari }}
                        <span id="badge-{{ $hari }}" class="ml-2 text-[10px] font-black bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full {{ isset($existing_jadwal[$hari]) ? '' : 'hidden' }}">
                            {{ isset($existing_jadwal[$hari]) ? $existing_jadwal[$hari]->count() : '' }}
                        </span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Tab Content --}}
            @php $currentActiveHari = $hariList[0]; @endphp
            @foreach($hariList as $i => $hari)
            @php 
                $rows = isset($existing_jadwal[$hari]) ? $existing_jadwal[$hari] : collect([null]); 
                if(old('jadwal.'.$hari)) {
                    $rows = collect(old('jadwal.'.$hari))->map(fn($v) => (object)$v);
                }
            @endphp
            <div id="tab-{{ $hari }}" class="tab-content p-6 {{ $i !== 0 ? 'hidden' : '' }} animate-in fade-in duration-300">

                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h4 class="font-black text-slate-700 text-base flex items-center gap-2">
                            Jadwal Hari {{ $hari }}
                        </h4>
                        <p class="text-xs text-slate-400 mt-0.5" id="count-{{ $hari }}">{{ $rows->count() }} mata pelajaran</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="clearDay('{{ $hari }}')" class="text-[11px] font-bold text-red-400 hover:text-red-600 transition">Hapus Semua</button>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-2xl border border-slate-100 bg-white">
                    <table class="w-full text-left" style="min-width:620px;">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-5 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest w-12">No</th>
                                <th class="px-5 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest">Mata Pelajaran — Guru</th>
                                <th class="px-5 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest w-40">Jam Mulai</th>
                                <th class="px-5 py-4 text-[11px] font-black text-slate-400 uppercase tracking-widest w-40">Jam Selesai</th>
                                <th class="px-5 py-4 text-center w-14"></th>
                            </tr>
                        </thead>
                        <tbody id="rows-{{ $hari }}" class="divide-y divide-slate-100">
                            @foreach($rows as $idx => $item)
                            <tr class="jadwal-row group hover:bg-slate-50/50 transition-colors" data-hari="{{ $hari }}">
                                <td class="px-5 py-4">
                                    <span class="row-num text-xs font-black text-slate-300 group-hover:text-blue-400 transition-colors">{{ $idx + 1 }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <select name="jadwal[{{ $hari }}][{{ $idx }}][id_pemetaan_akademik]"
                                        class="pemetaan-sel w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition font-medium"
                                        data-hari="{{ $hari }}">
                                        @if($selectedKelasId && isset($pemetaanByKelas[$selectedKelasId]))
                                            <option value="">-- Pilih Mapel / Guru --</option>
                                            @foreach($pemetaanByKelas[$selectedKelasId] as $p)
                                                <option value="{{ $p['id'] }}" {{ (isset($item->id_pemetaan_akademik) && $item->id_pemetaan_akademik == $p['id']) ? 'selected' : '' }}>
                                                    {{ $p['label'] }}
                                                </option>
                                            @endforeach
                                        @elseif($selectedKelasId)
                                            <option value="">Belum ada mapping mapel & guru untuk kelas ini</option>
                                        @else
                                            <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                                        @endif
                                    </select>
                                </td>
                                <td class="px-5 py-4">
                                    <input type="time" name="jadwal[{{ $hari }}][{{ $idx }}][jam_mulai]"
                                        class="jam-mulai w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition font-bold text-slate-600"
                                        value="{{ isset($item->jam_mulai) ? substr($item->jam_mulai,0,5) : '07:30' }}" data-hari="{{ $hari }}">
                                </td>
                                <td class="px-5 py-4">
                                    <input type="time" name="jadwal[{{ $hari }}][{{ $idx }}][jam_selesai]"
                                        class="jam-selesai w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition font-bold text-slate-600"
                                        value="{{ isset($item->jam_selesai) ? substr($item->jam_selesai,0,5) : '09:00' }}" data-hari="{{ $hari }}">
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <button type="button" onclick="removeRow(this)" class="w-9 h-9 rounded-xl hover:bg-red-50 text-slate-300 hover:text-red-500 transition-all flex items-center justify-center mx-auto hover:rotate-90">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <button type="button" onclick="addRow('{{ $hari }}')"
                    class="mt-5 w-full flex items-center justify-center gap-2 px-6 py-4 text-sm font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 border-2 border-dashed border-blue-200 rounded-2xl transition-all group">
                    <div class="w-6 h-6 rounded-lg bg-blue-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    Tambah Slot Pelajaran {{ $hari }}
                </button>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Step 3: Simpan --}}
<div class="bg-slate-900 rounded-3xl shadow-2xl p-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full -mr-32 -mt-32"></div>
    <div class="flex flex-col md:flex-row items-center justify-between gap-8 relative">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            </div>
            <div>
                <p class="font-black text-lg">Langkah 3: Simpan & Sinkronkan</p>
                <p class="text-blue-300 text-sm font-medium">Jadwal guru akan otomatis diperbarui setelah Anda menekan tombol simpan.</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
            <button type="button" onclick="clearAll()"
                class="px-8 py-4 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-2xl text-sm font-bold transition-all border border-slate-700">
                Reset Form
            </button>
            <button type="submit" id="btn-submit"
                class="flex items-center justify-center gap-3 px-10 py-4 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-2xl text-base transition-all shadow-xl shadow-blue-500/20 hover:-translate-y-1">
                <span>Sinkronkan Jadwal</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </div>
    </div>

    {{-- Summary --}}
    <div class="mt-8 pt-8 border-t border-white/10 grid grid-cols-3 md:grid-cols-6 gap-4" id="summary-bar">
        @foreach($hariList as $hari)
        <div class="text-center p-3 rounded-2xl bg-white/5 border border-white/5 transition-all hover:bg-white/10">
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ substr($hari,0,3) }}</p>
            <p id="sum-{{ $hari }}" class="text-xl font-black text-slate-600 mt-1">0</p>
        </div>
        @endforeach
    </div>
</div>

</form>
</div>

{{-- Script Logic --}}
<script>
const PEMETAAN_BY_KELAS = @json($pemetaanByKelas);
const HARI_LIST = @json($hariList);

// Indeks baris per hari
const rowIndex = {};
HARI_LIST.forEach(h => {
    rowIndex[h] = document.querySelectorAll(`#rows-${h} tr`).length;
});

// ===== Global Active Day =====
let activeHari = HARI_LIST[0];

// ===== Switch Tab =====
function switchTab(hari) {
    activeHari = hari;
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'border-slate-200', 'text-blue-600');
        btn.classList.add('bg-transparent', 'border-transparent', 'text-slate-400');
    });
    document.getElementById('tab-' + hari).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-btn-' + hari);
    activeBtn.classList.add('bg-white', 'border-slate-200', 'text-blue-600');
    activeBtn.classList.remove('bg-transparent', 'border-transparent', 'text-slate-400');
}

// ===== Tambah Mapping Spesifik =====
function addMappingToCurrentDay(id, label) {
    const hari = activeHari;
    const tbody = document.getElementById('rows-' + hari);
    
    // Jika ada baris kosong pertama (tanpa value), gunakan itu
    const firstRow = tbody.querySelector('.jadwal-row');
    const firstSel = firstRow ? firstRow.querySelector('.pemetaan-sel') : null;
    
    if (tbody.querySelectorAll('.jadwal-row').length === 1 && firstSel && !firstSel.value) {
        firstSel.value = id;
    } else {
        addRow(hari, id);
    }
    updateCount(hari);
    updateSummary();
}

// ===== Auto Fill dari Semua Mapping =====
function autoFillFromMapping() {
    const kelasId = document.getElementById('sel-kelas').value;
    if (!kelasId || !PEMETAAN_BY_KELAS[kelasId]) return;
    
    if (!confirm('Hapus jadwal hari ' + activeHari + ' dan isi otomatis dari pemetaan?')) return;
    
    const tbody = document.getElementById('rows-' + activeHari);
    tbody.innerHTML = '';
    rowIndex[activeHari] = 0;
    
    PEMETAAN_BY_KELAS[kelasId].forEach(p => {
        addRow(activeHari, p.id);
    });
}

// ===== Tambah Baris =====
function addRow(hari, initialId = '') {
    const tbody = document.getElementById('rows-' + hari);
    const idx   = rowIndex[hari];
    rowIndex[hari]++;

    const tr = document.createElement('tr');
    tr.className = 'jadwal-row group hover:bg-slate-50/50 transition-colors';
    tr.dataset.hari = hari;

    // Auto-fill jam
    const lastRow = tbody.querySelector('tr:last-child');
    let defaultMulai = '07:30';
    if (lastRow) {
        const lastSelesai = lastRow.querySelector('.jam-selesai');
        if (lastSelesai && lastSelesai.value) defaultMulai = lastSelesai.value;
    }
    const defaultSelesai = addMinutes(defaultMulai, 90);

    const kelasId = document.getElementById('sel-kelas').value;
    const opts = buildOptions(kelasId, initialId);

    tr.innerHTML = `
        <td class="px-5 py-4"><span class="row-num text-xs font-black text-slate-300 group-hover:text-blue-400 transition-colors">${idx + 1}</span></td>
        <td class="px-5 py-4">
            <select name="jadwal[${hari}][${idx}][id_pemetaan_akademik]"
                class="pemetaan-sel w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition font-medium"
                data-hari="${hari}">
                ${opts}
            </select>
        </td>
        <td class="px-5 py-4">
            <input type="time" name="jadwal[${hari}][${idx}][jam_mulai]"
                class="jam-mulai w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition font-bold text-slate-600"
                value="${defaultMulai}" data-hari="${hari}">
        </td>
        <td class="px-5 py-4">
            <input type="time" name="jadwal[${hari}][${idx}][jam_selesai]"
                class="jam-selesai w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100 transition font-bold text-slate-600"
                value="${defaultSelesai}" data-hari="${hari}">
        </td>
        <td class="px-5 py-4 text-center">
            <button type="button" onclick="removeRow(this)" class="w-9 h-9 rounded-xl hover:bg-red-50 text-slate-300 hover:text-red-500 transition-all flex items-center justify-center mx-auto hover:rotate-90">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    updateCount(hari);
    updateSummary();
}

// ===== Hapus Baris =====
function removeRow(btn) {
    const tr   = btn.closest('tr');
    const hari = tr.dataset.hari;
    const tbody = document.getElementById('rows-' + hari);
    tr.remove();
    renumberRows(hari);
    updateCount(hari);
    updateSummary();
}

function clearDay(hari) {
    if (!confirm('Kosongkan semua jadwal untuk hari ' + hari + '?')) return;
    const tbody = document.getElementById('rows-' + hari);
    tbody.innerHTML = '';
    rowIndex[hari] = 0;
    updateCount(hari);
    updateSummary();
    addRow(hari); // Sisakan 1 baris kosong
}

// ===== Build Options =====
function buildOptions(kelasId, selectedId = '') {
    if (!kelasId) return '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
    if (!PEMETAAN_BY_KELAS[kelasId] || PEMETAAN_BY_KELAS[kelasId].length === 0) {
        return '<option value="">Belum ada mapping mapel & guru untuk kelas ini</option>';
    }
    return '<option value="">-- Pilih Mapel / Guru --</option>' + PEMETAAN_BY_KELAS[kelasId].map(p =>
        `<option value="${p.id}" ${p.id == selectedId ? 'selected' : ''}>${p.label}</option>`
    ).join('');
}

// ===== Auto-fill jam =====
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('jam-selesai')) {
        const hari = e.target.dataset.hari;
        const row  = e.target.closest('tr');
        const nextRow = row.nextElementSibling;
        if (nextRow && nextRow.dataset.hari === hari) {
            const nextMulai = nextRow.querySelector('.jam-mulai');
            if (nextMulai && !nextMulai._userEdited) {
                nextMulai.value = e.target.value;
            }
        }
    }
    if (e.target.classList.contains('jam-mulai')) {
        e.target._userEdited = true;
    }
    updateSummary();
    const hari = e.target.dataset?.hari;
    if (hari) updateCount(hari);
});

// ===== Renumber baris =====
function renumberRows(hari) {
    const rows = document.querySelectorAll(`#rows-${hari} .jadwal-row`);
    rows.forEach((row, i) => {
        row.querySelector('.row-num').textContent = i + 1;
    });
}

// ===== Update counter =====
function updateCount(hari) {
    const rows  = document.querySelectorAll(`#rows-${hari} .jadwal-row`);
    let filled  = 0;
    rows.forEach(r => {
        const sel = r.querySelector('.pemetaan-sel');
        if (sel && sel.value) filled++;
    });
    document.getElementById('count-' + hari).textContent = rows.length + ' mata pelajaran';
    const badge = document.getElementById('badge-' + hari);
    if (filled > 0) {
        badge.textContent = filled;
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

// ===== Update summary =====
function updateSummary() {
    HARI_LIST.forEach(hari => {
        const rows = document.querySelectorAll(`#rows-${hari} .jadwal-row`);
        let n = 0;
        rows.forEach(r => { if (r.querySelector('.pemetaan-sel')?.value) n++; });
        const el = document.getElementById('sum-' + hari);
        el.textContent = n;
        el.className = n > 0 ? 'text-xl font-black text-blue-500 mt-1' : 'text-xl font-black text-slate-700 mt-1';
    });
}

function addMinutes(time, mins) {
    const [h, m] = time.split(':').map(Number);
    const total  = h * 60 + m + mins;
    return String(Math.floor(total / 60) % 24).padStart(2, '0') + ':' + String(total % 60).padStart(2, '0');
}

function clearAll() {
    if (!confirm('Reset form ke kondisi awal?')) return;
    window.location.reload();
}

window.addEventListener('DOMContentLoaded', () => {
    HARI_LIST.forEach(h => updateCount(h));
    updateSummary();
});

document.getElementById('form-bulk').addEventListener('submit', function(e) {
    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Menyingkronkan...';
});
</script>
@endsection
