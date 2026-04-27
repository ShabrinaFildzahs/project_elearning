@extends('layouts.app')
@section('title', 'Input Jadwal Massal')
@section('page_title', 'Input Jadwal Massal')

@section('content')
<div class="space-y-5">

@php
    $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $selectedKelasId = old('id_kelas', request('kelas_id'));
    // Kelompokkan pemetaan per kelas untuk JS
    $pemetaanByKelas = [];
    foreach ($data_pemetaan as $p) {
        $pemetaanByKelas[$p->id_kelas][] = [
            'id'    => $p->id,
            'label' => ($p->mataPelajaran->nama ?? '-') . ' — ' . ($p->guru->nama ?? '-'),
        ];
    }
@endphp

{{-- Alert Error --}}
@if($errors->has('bulk'))
<div id="alert-err" class="flex items-start gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z"/></svg>
    <div class="flex-1">
        <p class="font-bold mb-1">Terdapat Kesalahan:</p>
        @foreach(explode("\n", $errors->first('bulk')) as $err)
            <p class="text-sm">{{ $err }}</p>
        @endforeach
    </div>
    <button onclick="document.getElementById('alert-err').remove()" class="text-red-400 hover:text-red-600">✕</button>
</div>
@endif

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <p class="text-[10px] lg:text-xs text-slate-400 font-medium">Input semua jadwal untuk 1 kelas dalam 1 minggu sekaligus</p>
    </div>
    <a href="{{ route('admin.schedules.index') }}"
        class="w-full sm:w-auto flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50 transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Jadwal
    </a>
</div>

<form action="{{ route('admin.schedules.bulk') }}" method="POST" id="form-bulk">
@csrf

{{-- Step 1: Pilih Kelas --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-5">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-600">1</div>
        <div>
            <p class="font-bold text-slate-800">Pilih Kelas</p>
            <p class="text-xs text-slate-400">Pilih kelas yang akan dibuatkan jadwal mingguan</p>
        </div>
    </div>
    <div class="max-w-xs">
        <select name="id_kelas" id="sel-kelas" required
            class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-400/20 transition font-medium">
            <option value="">-- Pilih Kelas --</option>
            @foreach($data_kelas as $kelas)
            <option value="{{ $kelas->id }}" {{ $selectedKelasId == $kelas->id ? 'selected' : '' }}>
                {{ $kelas->nama }}
            </option>
            @endforeach
        </select>
    </div>
</div>

{{-- Step 2: Tab Hari --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-5">
    <div class="px-6 pt-5 pb-0 border-b border-slate-100">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-600">2</div>
            <div>
                <p class="font-bold text-slate-800">Isi Jadwal Per Hari</p>
                <p class="text-xs text-slate-400">Klik tab hari lalu tambahkan mata pelajaran</p>
            </div>
        </div>

        {{-- Tab Buttons --}}
        <div class="flex gap-1 -mb-px overflow-x-auto no-scrollbar scroll-smooth">
            @foreach($hariList as $i => $hari)
            <button type="button" onclick="switchTab('{{ $hari }}')"
                id="tab-btn-{{ $hari }}"
                class="tab-btn whitespace-nowrap px-5 py-2.5 text-sm font-semibold rounded-t-xl border border-b-0 transition relative
                    {{ $i === 0 ? 'bg-white border-slate-200 text-blue-600' : 'bg-slate-50 border-transparent text-slate-400 hover:text-slate-600 hover:bg-white' }}">
                {{ $hari }}
                <span id="badge-{{ $hari }}" class="ml-1.5 text-[10px] font-bold bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full hidden"></span>
            </button>
            @endforeach
        </div>
    </div>

    {{-- Tab Content --}}
    @foreach($hariList as $i => $hari)
    <div id="tab-{{ $hari }}" class="tab-content p-6 {{ $i !== 0 ? 'hidden' : '' }}">

        {{-- Table Header --}}
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-bold text-slate-700 text-sm">Jadwal Hari {{ $hari }}</h4>
            <span id="count-{{ $hari }}" class="text-xs text-slate-400 font-medium">0 mata pelajaran</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" style="min-width:560px;">
                <thead>
                    <tr class="bg-slate-50 rounded-xl">
                        <th class="px-3 py-2.5 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider rounded-l-xl w-8">#</th>
                        <th class="px-3 py-2.5 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran — Guru</th>
                        <th class="px-3 py-2.5 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider w-32">Jam Mulai</th>
                        <th class="px-3 py-2.5 text-left text-[11px] font-bold text-slate-400 uppercase tracking-wider w-32">Jam Selesai</th>
                        <th class="px-3 py-2.5 text-center text-[11px] font-bold text-slate-400 uppercase tracking-wider rounded-r-xl w-10"></th>
                    </tr>
                </thead>
                <tbody id="rows-{{ $hari }}" class="divide-y divide-slate-50">
                    {{-- Baris awal --}}
                    <tr class="jadwal-row" data-hari="{{ $hari }}">
                        <td class="px-3 py-2.5">
                            <span class="row-num text-xs font-bold text-slate-400">1</span>
                        </td>
                        <td class="px-3 py-2.5">
                            <select name="jadwal[{{ $hari }}][0][id_pemetaan_akademik]"
                                class="pemetaan-sel w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-blue-400 transition"
                                data-hari="{{ $hari }}">
                                <option value="">-- Pilih Mapel / Guru --</option>
                            </select>
                        </td>
                        <td class="px-3 py-2.5">
                            <input type="time" name="jadwal[{{ $hari }}][0][jam_mulai]"
                                class="jam-mulai w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-blue-400 transition"
                                value="07:00" data-hari="{{ $hari }}">
                        </td>
                        <td class="px-3 py-2.5">
                            <input type="time" name="jadwal[{{ $hari }}][0][jam_selesai]"
                                class="jam-selesai w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-blue-400 transition"
                                value="08:30" data-hari="{{ $hari }}">
                        </td>
                        <td class="px-3 py-2.5 text-center">
                            <button type="button" onclick="removeRow(this)" class="w-7 h-7 rounded-lg hover:bg-red-50 text-slate-300 hover:text-red-500 transition flex items-center justify-center mx-auto">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button type="button" onclick="addRow('{{ $hari }}')"
            class="mt-3 flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Baris {{ $hari }}
        </button>
    </div>
    @endforeach
</div>

{{-- Step 3: Simpan --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-600">3</div>
            <div>
                <p class="font-bold text-slate-800">Simpan Semua Jadwal</p>
                <p class="text-xs text-slate-400">Sistem akan mendeteksi bentrok secara otomatis sebelum menyimpan</p>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="clearAll()"
                class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50 transition">
                Reset Semua
            </button>
            <button type="submit" id="btn-submit"
                class="flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition shadow-sm shadow-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                Simpan Semua Jadwal Minggu Ini
            </button>
        </div>
    </div>

    {{-- Summary --}}
    <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-3 md:grid-cols-6 gap-3" id="summary-bar">
        @foreach($hariList as $hari)
        <div class="text-center p-2 rounded-xl bg-slate-50 border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase">{{ substr($hari,0,3) }}</p>
            <p id="sum-{{ $hari }}" class="text-lg font-bold text-slate-300 mt-0.5">0</p>
        </div>
        @endforeach
    </div>
</div>

</form>
</div>

{{-- Data pemetaan untuk JS --}}
<script>
const PEMETAAN_BY_KELAS = @json($pemetaanByKelas);
const HARI_LIST = @json($hariList);

// Indeks baris per hari (untuk naming input)
const rowIndex = {};
HARI_LIST.forEach(h => rowIndex[h] = 1); // mulai dari 1 karena baris 0 sudah ada

// ===== Switch Tab =====
function switchTab(hari) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'border-slate-200', 'text-blue-600');
        btn.classList.add('bg-slate-50', 'border-transparent', 'text-slate-400');
    });
    document.getElementById('tab-' + hari).classList.remove('hidden');
    const activeBtn = document.getElementById('tab-btn-' + hari);
    activeBtn.classList.add('bg-white', 'border-slate-200', 'text-blue-600');
    activeBtn.classList.remove('bg-slate-50', 'border-transparent', 'text-slate-400');
}

// ===== Tambah Baris =====
function addRow(hari) {
    const tbody = document.getElementById('rows-' + hari);
    const idx   = rowIndex[hari];
    rowIndex[hari]++;

    const tr = document.createElement('tr');
    tr.className = 'jadwal-row';
    tr.dataset.hari = hari;

    // Ambil jam selesai baris terakhir untuk auto-fill
    const lastSelesai = tbody.querySelector('tr:last-child .jam-selesai');
    const defaultMulai = lastSelesai ? lastSelesai.value : '07:00';
    const defaultSelesai = addMinutes(defaultMulai, 90);

    // Build options
    const kelasId = document.getElementById('sel-kelas').value;
    const opts = buildOptions(kelasId);

    tr.innerHTML = `
        <td class="px-3 py-2.5"><span class="row-num text-xs font-bold text-slate-400">${idx + 1}</span></td>
        <td class="px-3 py-2.5">
            <select name="jadwal[${hari}][${idx}][id_pemetaan_akademik]"
                class="pemetaan-sel w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-blue-400 transition"
                data-hari="${hari}">
                <option value="">-- Pilih Mapel / Guru --</option>
                ${opts}
            </select>
        </td>
        <td class="px-3 py-2.5">
            <input type="time" name="jadwal[${hari}][${idx}][jam_mulai]"
                class="jam-mulai w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-blue-400 transition"
                value="${defaultMulai}" data-hari="${hari}">
        </td>
        <td class="px-3 py-2.5">
            <input type="time" name="jadwal[${hari}][${idx}][jam_selesai]"
                class="jam-selesai w-full px-3 py-2 text-sm rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-blue-400 transition"
                value="${defaultSelesai}" data-hari="${hari}">
        </td>
        <td class="px-3 py-2.5 text-center">
            <button type="button" onclick="removeRow(this)" class="w-7 h-7 rounded-lg hover:bg-red-50 text-slate-300 hover:text-red-500 transition flex items-center justify-center mx-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
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
    if (tbody.querySelectorAll('tr').length <= 1) {
        alert('Minimal harus ada 1 baris per hari. Kosongkan saja jika tidak ada jadwal.');
        return;
    }
    tr.remove();
    renumberRows(hari);
    updateCount(hari);
    updateSummary();
}

// ===== Build Options =====
function buildOptions(kelasId) {
    if (!kelasId || !PEMETAAN_BY_KELAS[kelasId]) return '';
    return PEMETAAN_BY_KELAS[kelasId].map(p =>
        `<option value="${p.id}">${p.label}</option>`
    ).join('');
}

// ===== Saat Kelas Dipilih: Update semua dropdown mapel =====
document.getElementById('sel-kelas').addEventListener('change', function () {
    const kelasId = this.value;
    const opts    = buildOptions(kelasId);
    document.querySelectorAll('.pemetaan-sel').forEach(sel => {
        const curVal = sel.value;
        sel.innerHTML = '<option value="">-- Pilih Mapel / Guru --</option>' + opts;
        if (curVal) sel.value = curVal;
    });
});

// ===== Auto-fill jam saat jam_selesai berubah =====
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

// ===== Update counter per hari =====
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

// ===== Update summary bar =====
function updateSummary() {
    HARI_LIST.forEach(hari => {
        const rows = document.querySelectorAll(`#rows-${hari} .jadwal-row`);
        let n = 0;
        rows.forEach(r => { if (r.querySelector('.pemetaan-sel')?.value) n++; });
        const el = document.getElementById('sum-' + hari);
        el.textContent = n;
        el.className = n > 0 ? 'text-lg font-bold text-blue-600 mt-0.5' : 'text-lg font-bold text-slate-300 mt-0.5';
    });
}

// ===== Add minutes helper =====
function addMinutes(time, mins) {
    const [h, m] = time.split(':').map(Number);
    const total  = h * 60 + m + mins;
    return String(Math.floor(total / 60) % 24).padStart(2, '0') + ':' + String(total % 60).padStart(2, '0');
}

// ===== Reset semua =====
function clearAll() {
    if (!confirm('Reset semua baris yang sudah diisi?')) return;
    HARI_LIST.forEach(hari => {
        const tbody = document.getElementById('rows-' + hari);
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((r, i) => {
            if (i > 0) r.remove();
            else {
                r.querySelector('.pemetaan-sel').value = '';
                r.querySelector('.jam-mulai').value = '07:00';
                r.querySelector('.jam-selesai').value = '08:30';
            }
        });
        rowIndex[hari] = 1;
        updateCount(hari);
    });
    updateSummary();
}

// ===== Init: isi dropdown jika kelas sudah dipilih =====
window.addEventListener('DOMContentLoaded', () => {
    const kelasId = document.getElementById('sel-kelas').value;
    if (kelasId) {
        const opts = buildOptions(kelasId);
        document.querySelectorAll('.pemetaan-sel').forEach(sel => {
            sel.innerHTML = '<option value="">-- Pilih Mapel / Guru --</option>' + opts;
        });
    }
    HARI_LIST.forEach(h => updateCount(h));
    updateSummary();
});

// ===== Submit confirm =====
document.getElementById('form-bulk').addEventListener('submit', function(e) {
    const kelas = document.getElementById('sel-kelas').value;
    if (!kelas) {
        e.preventDefault();
        alert('Pilih kelas terlebih dahulu!');
        return;
    }
    let total = 0;
    HARI_LIST.forEach(hari => {
        document.querySelectorAll(`#rows-${hari} .pemetaan-sel`).forEach(sel => {
            if (sel.value) total++;
        });
    });
    if (total === 0) {
        e.preventDefault();
        alert('Belum ada jadwal yang diisi!');
        return;
    }
    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Menyimpan...';
});
</script>
@endsection
