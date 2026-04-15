@extends('layouts.app')

@section('title', 'Detail Assignment')
@section('page_title', 'Tugas & Kuis')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-in fade-in duration-500">

    {{-- Header Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('siswa.assignments') }}"
           class="group flex items-center gap-2 px-4 py-2 bg-white border border-slate-100 rounded-2xl text-sm font-bold text-slate-500 hover:text-blue-600 hover:border-blue-100 transition-all duration-300">
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            KEMBALI KE DAFTAR
        </a>
        <div class="flex items-center gap-3">
            @if($submission)
                <span class="flex items-center gap-1.5 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-2xl text-xs font-black uppercase tracking-wider border border-emerald-100">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    TERKIRIM
                </span>
            @elseif($assignment->deadline < now())
                <span class="flex items-center gap-1.5 px-4 py-2 bg-red-50 text-red-600 rounded-2xl text-xs font-black uppercase tracking-wider border border-red-100">
                    <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
                    TERLEWAT
                </span>
            @else
                <span class="flex items-center gap-1.5 px-4 py-2 bg-blue-50 text-blue-600 rounded-2xl text-xs font-black uppercase tracking-wider border border-blue-100">
                    <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                    AKTIF
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div id="alert-success" class="flex items-center gap-3 p-4 rounded-3xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium shadow-sm">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('alert-success').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600 transition">✕</button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left: Assignment Details --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-8 md:p-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-{{ $assignment->type == 'kuis' ? 'purple' : 'blue' }}-50 flex items-center justify-center text-{{ $assignment->type == 'kuis' ? 'purple' : 'blue' }}-600">
                        @if($assignment->type == 'kuis')
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        @else
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-black text-slate-800 leading-tight">{{ $assignment->title }}</h1>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1">Assignment Detail</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
                    <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-100/50">
                        <div class="flex items-center gap-3 text-slate-400 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">Mata Pelajaran</span>
                        </div>
                        <p class="text-base font-black text-slate-700">{{ $assignment->academicMap->subject->name ?? '-' }}</p>
                    </div>
                    <div class="bg-slate-50/50 rounded-3xl p-6 border border-slate-100/50">
                        <div class="flex items-center gap-3 text-slate-400 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">Kelas & Pengajar</span>
                        </div>
                        <p class="text-base font-black text-slate-700">{{ $assignment->academicMap->class->name ?? '-' }}</p>
                        <p class="text-xs font-bold text-slate-400 mt-1">{{ $assignment->academicMap->teacher->name ?? '-' }}</p>
                    </div>
                </div>

                <div class="prose prose-slate max-w-none">
                    <h4 class="text-xs font-black text-slate-300 uppercase tracking-[0.2em] mb-4">Instruksi & Deskripsi</h4>
                    <div class="text-slate-600 text-base leading-relaxed p-6 bg-slate-50/30 rounded-3xl border border-slate-100/30 whitespace-pre-line">
                        {{ $assignment->description ?? 'Tidak ada deskripsi atau instruksi khusus untuk tugas ini.' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Submission Panel --}}
        <div class="space-y-6">
            {{-- Status Card --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 group">
                <h4 class="text-xs font-black text-slate-300 uppercase tracking-[0.2em] mb-6">Status Pengumpulan</h4>

                @if($submission)
                    <div class="text-center py-6">
                        @if($submission->grade !== null)
                            <div class="relative inline-block mb-4">
                                <div class="w-24 h-24 rounded-full border-4 border-emerald-500/20 flex items-center justify-center p-2">
                                    <div class="w-full h-full rounded-full bg-emerald-500 flex flex-col items-center justify-center text-white shadow-xl shadow-emerald-200">
                                        <span class="text-3xl font-black">{{ $submission->grade }}</span>
                                    </div>
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-white border-2 border-emerald-500 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                            <h5 class="text-lg font-black text-slate-800">Sudah Dinilai</h5>
                            <p class="text-sm text-slate-400 font-medium">Nilai akademik kamu untuk tugas ini.</p>
                        @else
                            <div class="w-20 h-20 bg-blue-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 animate-bounce">
                                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h5 class="text-lg font-black text-slate-800">Menunggu Penilaian</h5>
                            <p class="text-sm text-slate-400 font-medium">Tugasmu sedang diperiksa oleh guru.</p>
                        @endif

                        <div class="mt-8 p-4 bg-slate-50 rounded-3xl border border-slate-100 flex items-center gap-3 text-left">
                            <div class="shrink-0 w-10 h-10 rounded-2xl bg-white border border-slate-200 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Kumpul</p>
                                <p class="text-xs font-black text-slate-700 truncate">{{ $submission->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        @if($assignment->deadline >= now() && $submission->grade === null)
                            <div class="mt-6 pt-6 border-t border-slate-100">
                                <p class="text-xs font-bold text-slate-400 mb-4 uppercase tracking-wider">Mau update jawaban?</p>
                                <form action="{{ route('siswa.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="file" id="file_resubmit" class="hidden" onchange="this.form.submit()">
                                    <button type="button" onclick="document.getElementById('file_resubmit').click()"
                                            class="w-full py-4 px-6 rounded-2xl bg-white border-2 border-slate-100 text-xs font-black text-slate-600 hover:border-blue-500 hover:text-blue-600 transition-all duration-300 flex items-center justify-center gap-2 active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        GANTI FILE JAWABAN
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @elseif($assignment->deadline < now())
                     <div class="text-center py-10 opacity-60">
                         <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                             <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                         </div>
                         <h5 class="text-lg font-black text-slate-800">Waktu Berakhir</h5>
                         <p class="text-sm text-slate-500 font-medium px-4">Maaf, periode pengumpulan tugas ini sudah berakhir secara otomatis.</p>
                     </div>
                @else
                    {{-- Active Upload Area --}}
                    <form action="{{ route('siswa.assignments.submit', $assignment) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="relative group/upload">
                            <input type="file" name="file" id="submission_file" class="hidden" required
                                   onchange="const n=this.files[0]?.name; document.getElementById('filename').textContent=n; document.getElementById('upload_placeholder').classList.add('hidden'); document.getElementById('upload_active').classList.remove('hidden');">

                            <div onclick="document.getElementById('submission_file').click()"
                                 class="w-full aspect-square md:aspect-auto md:h-64 cursor-pointer border-3 border-dashed border-slate-100 rounded-[2rem] bg-slate-50/50 hover:bg-white hover:border-blue-400 hover:shadow-xl hover:shadow-blue-50 transition-all duration-300 flex flex-col items-center justify-center p-6 group-hover/upload:scale-[1.02]">

                                <div id="upload_placeholder" class="text-center">
                                    <div class="w-20 h-20 bg-blue-50 rounded-[1.8rem] flex items-center justify-center mx-auto mb-4 group-hover/upload:scale-110 group-hover/upload:bg-blue-600 transition-all duration-500">
                                        <svg class="w-10 h-10 text-blue-600 group-hover/upload:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    </div>
                                    <h5 class="text-sm font-black text-slate-700">Pilih File Jawaban</h5>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">PDF, DOCX, ZIP (MAX 20MB)</p>
                                </div>

                                <div id="upload_active" class="hidden text-center animate-in zoom-in-90 duration-300">
                                    <div class="w-20 h-20 bg-emerald-50 rounded-[1.8rem] flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p id="filename" class="text-xs font-black text-slate-800 break-all px-4 mb-2"></p>
                                    <span class="text-[10px] font-black text-blue-600 underline">Klik untuk ganti file</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full py-5 rounded-[2rem] bg-blue-600 text-sm font-black text-white hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all active:scale-95 duration-200 uppercase tracking-widest flex items-center justify-center gap-3">
                            KIRIM JAWABAN
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </button>

                        @error('file')
                            <p class="text-center text-red-500 text-[10px] font-black uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </form>
                @endif
            </div>

            {{-- Deadline Card --}}
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2.5rem] p-8 text-white shadow-xl shadow-slate-200">
                <div class="flex items-center gap-3 mb-6 opacity-60">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">Waktu Tersisa</span>
                </div>

                @php
                    $diff = now()->diff($assignment->deadline);
                    $isExpired = $assignment->deadline < now();
                @endphp

                @if($isExpired)
                    <div class="text-3xl font-black text-red-400">EXPIRED</div>
                    <p class="text-xs font-bold text-slate-400 mt-2">Selesai pada {{ $assignment->deadline->translatedFormat('d F Y, H:i') }}</p>
                @else
                    <div class="flex items-end gap-1">
                        <span class="text-4xl font-black leading-none">{{ $diff->d }}</span>
                        <span class="text-xs font-bold uppercase tracking-widest mb-1 ml-1 opacity-60">Hari</span>
                        <span class="text-4xl font-black leading-none ml-3">{{ $diff->h }}</span>
                        <span class="text-xs font-bold uppercase tracking-widest mb-1 ml-1 opacity-60">Jam</span>
                    </div>
                    <p class="text-xs font-medium text-slate-400 mt-4 leading-relaxed">
                        Segera kumpulkan tugasmu sebelum waktu habis untuk menghindari pinalti atau penolakan otomatis.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-subtle { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    .animate-bounce { animation: bounce-subtle 3s infinite ease-in-out; }
</style>
@endsection
