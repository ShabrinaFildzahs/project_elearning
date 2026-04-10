@extends('layouts.app')

@section('title', $assignment->title)
@section('page_title', 'Detail Tugas')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <a href="{{ route('siswa.assignments') }}" class="inline-flex items-center space-x-2 text-sm text-slate-500 hover:text-blue-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        <span>Kembali ke Daftar Tugas</span>
    </a>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-5 py-4 rounded-xl flex items-center space-x-2">
            <span>✅</span><span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-5 py-4 rounded-xl">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Assignment Detail --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-3 py-1 text-xs font-bold rounded-full uppercase
                        @if($assignment->type == 'kuis') bg-purple-100 text-purple-600
                        @else bg-blue-100 text-blue-600 @endif">
                        {{ $assignment->type }}
                    </span>
                    <span class="px-3 py-1 text-xs font-bold rounded-full
                        @if($assignment->deadline < now() && !$submission) bg-red-100 text-red-600
                        @elseif($submission) bg-emerald-100 text-emerald-600
                        @else bg-slate-100 text-slate-600 @endif">
                        @if($submission) ✅ Sudah Dikumpul
                        @elseif($assignment->deadline < now()) ❌ Terlambat
                        @else ⏳ Menunggu Pengumpulan @endif
                    </span>
                </div>
                <h2 class="text-xl font-bold text-slate-800">{{ $assignment->title }}</h2>
            </div>
        </div>
        <div class="p-6 space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Mata Pelajaran</p>
                    <p class="font-bold text-slate-700">{{ $assignment->academicMap->subject->name ?? '-' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Kelas</p>
                    <p class="font-bold text-slate-700">{{ $assignment->academicMap->class->name ?? '-' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 @if($assignment->deadline < now() && !$submission) bg-red-50 @endif">
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider mb-1">Deadline</p>
                    <p class="font-bold @if($assignment->deadline < now() && !$submission) text-red-600 @else text-slate-700 @endif">
                        {{ $assignment->deadline->format('d M Y') }}
                    </p>
                    <p class="text-xs @if($assignment->deadline < now() && !$submission) text-red-500 @else text-slate-400 @endif">
                        {{ $assignment->deadline->format('H:i') }} WIB
                    </p>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-slate-700 mb-2 text-sm uppercase tracking-wider">Deskripsi Tugas</h4>
                <div class="bg-slate-50 rounded-xl p-5 text-slate-700 text-sm leading-relaxed whitespace-pre-line">
                    {{ $assignment->description }}
                </div>
            </div>

            <div>
                <h4 class="font-bold text-slate-700 mb-1 text-sm">Pengajar</h4>
                <p class="text-slate-600 text-sm">{{ $assignment->academicMap->teacher->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Submission Section --}}
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Pengumpulan Tugas</h3>
        </div>
        <div class="p-6">
            @if($submission)
                <div class="space-y-4">
                    <div class="flex items-center space-x-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 text-xl">✅</div>
                        <div>
                            <p class="font-bold text-emerald-700">Tugas Berhasil Dikumpulkan!</p>
                            <p class="text-xs text-emerald-600">Dikirim pada {{ $submission->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($submission->grade !== null)
                        <div class="flex items-center justify-center p-8 bg-slate-50 rounded-xl">
                            <div class="text-center">
                                <div class="text-5xl font-black
                                    @if($submission->grade >= 80) text-emerald-500
                                    @elseif($submission->grade >= 60) text-blue-500
                                    @else text-red-500 @endif">
                                    {{ $submission->grade }}
                                </div>
                                <div class="text-sm text-slate-500 mt-1">Nilai Anda</div>
                            </div>
                        </div>
                    @else
                        <div class="p-4 bg-blue-50 rounded-xl text-center text-sm text-blue-600">
                            ⏳ Tugas sedang diperiksa oleh guru. Nilai akan segera muncul.
                        </div>
                    @endif

                    @if($assignment->deadline >= now())
                    <div class="border-t border-slate-100 pt-4">
                        <p class="text-xs text-slate-500 mb-3 font-medium">Ingin mengganti file jawaban?</p>
                        <form action="{{ route('siswa.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex items-center gap-3">
                                <input type="file" name="file" id="file_resubmit"
                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <button type="submit" class="shrink-0 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2 rounded-xl transition">
                                    Ganti File
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>

            @elseif($assignment->deadline < now())
                <div class="p-8 text-center">
                    <div class="text-4xl mb-3">⌛</div>
                    <p class="text-slate-600 font-semibold">Deadline telah lewat</p>
                    <p class="text-sm text-slate-400 mt-1">Waktu pengumpulan tugas sudah berakhir pada {{ $assignment->deadline->format('d M Y, H:i') }}</p>
                </div>

            @else
                <form action="{{ route('siswa.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Upload File Jawaban</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-blue-400 transition cursor-pointer"
                             onclick="document.getElementById('file_upload').click()">
                            <div class="text-3xl mb-2">📎</div>
                            <p class="text-sm font-medium text-slate-600">Klik untuk memilih file atau seret ke sini</p>
                            <p class="text-xs text-slate-400 mt-1">Maks. 20MB (PDF, DOC, DOCX, ZIP, dll)</p>
                            <input type="file" id="file_upload" name="file" class="hidden" required
                                   onchange="document.getElementById('file_name_display').textContent = this.files[0]?.name ?? ''">
                        </div>
                        <p id="file_name_display" class="text-xs text-blue-600 font-medium mt-2"></p>
                        @error('file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl transition flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span>Kumpulkan Tugas</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>
@endsection
