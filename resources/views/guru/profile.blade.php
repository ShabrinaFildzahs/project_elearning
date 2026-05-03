@extends('layouts.app')
@section('title', 'Profil Guru')
@section('page_title', 'Profil Pribadi')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-12">

    {{-- Alert System --}}
    @if(session('success'))
    <div id="alert-ok" class="animate-in fade-in slide-in-from-top-4 duration-300 flex items-center gap-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-bold shadow-sm">
        <div class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <span>{{ session('success') }}</span>
        <button onclick="document.getElementById('alert-ok').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">✕</button>
    </div>
    @endif

    @if($errors->any())
    <div class="animate-in fade-in slide-in-from-top-4 duration-300 p-4 rounded-2xl bg-red-50 border border-red-100 text-red-800 text-sm font-medium shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z"/></svg>
            <span class="font-bold">Gagal Memperbarui Profil:</span>
        </div>
        <ul class="list-disc list-inside space-y-1 ml-8">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button onclick="openModal()" class="mt-4 text-xs font-black text-red-600 underline">Buka Kembali Form Edit</button>
    </div>
    @endif

    <div class="relative mb-12">
        {{-- Header Background --}}
        <div class="h-48 rounded-3xl bg-gradient-to-r from-emerald-600 to-teal-500 shadow-lg overflow-hidden relative group">
            <div class="absolute inset-0 opacity-10">
                <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"></path>
                </svg>
            </div>
            {{-- Edit Button positioned top-right of header --}}
            <button onclick="openModal()" class="absolute top-6 right-6 px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white border border-white/30 rounded-xl flex items-center gap-2 transition-all transform hover:scale-105 active:scale-95 font-bold text-xs shadow-lg" title="Edit Profil">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Profil</span>
            </button>
        </div>

        {{-- Profile Card --}}
        <div class="absolute -bottom-12 left-8 flex items-end space-x-6">
            <div class="w-32 h-32 rounded-3xl bg-white p-2 shadow-xl relative group">
                <div class="w-full h-full rounded-2xl bg-emerald-100 flex items-center justify-center text-4xl font-bold text-emerald-600 border-4 border-emerald-50">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
            </div>
            <div class="pb-4">
                <div class="flex items-center gap-3">
                    <h1 class="text-3xl font-extrabold text-white drop-shadow-md">{{ $user->nama }}</h1>
                </div>
                <p class="text-slate-500 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Guru Pengampu · {{ $user->email }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-24">
        
        {{-- Left Column --}}
        <div class="space-y-6">
            <div class="glass-card rounded-3xl p-6 border border-slate-100 shadow-sm bg-white">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi Akun
                </h3>
                <div class="space-y-5">
                    <div>
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Email Aktif</p>
                        <p class="text-sm font-bold text-slate-700">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">No. HP / WhatsApp</p>
                        <p class="text-sm font-bold text-slate-700">{{ $user->no_hp }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Status Keamanan</p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Terenkripsi
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="glass-card rounded-3xl p-8 border border-slate-100 shadow-sm bg-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-full -mr-16 -mt-16"></div>
                
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-widest mb-8 flex items-center gap-2 relative">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Data Pribadi & Alamat
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Pendidikan Terakhir</p>
                        <p class="text-sm font-bold text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $user->pendidikan_terakhir }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tempat, Tanggal Lahir</p>
                        <p class="text-sm font-bold text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $user->tempat_lahir }}, {{ \Carbon\Carbon::parse($user->tanggal_lahir)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Jenis Kelamin</p>
                        <p class="text-sm font-bold text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $user->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap (Statis)</p>
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $user->alamat }}</p>
                            <p class="text-[10px] text-slate-400 mt-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Alamat hanya dapat diubah oleh administrator sistem.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-slate-100 flex justify-end">
                    <button onclick="openModal()" class="flex items-center gap-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl transition shadow-lg shadow-emerald-500/20 active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Akun & Password
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT PROFILE --}}
<div id="modal-edit" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div id="modal-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-in zoom-in-95 duration-300">
            <div class="bg-white px-8 pt-8 pb-6">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" id="modal-title">Edit Profil Akun</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-0.5">Ubah Email & Keamanan</p>
                        </div>
                    </div>
                    <button onclick="closeModal()" class="w-10 h-10 rounded-xl hover:bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('guru.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        {{-- Data Dasar --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Email Aktif</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 transition-all font-bold text-slate-700 text-sm">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required
                                class="w-full px-4 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 transition-all font-bold text-slate-700 text-sm">
                        </div>

                        {{-- Password Section --}}
                        <div class="pt-6 border-t border-slate-50">
                            <p class="text-[10px] font-black text-amber-500 uppercase tracking-widest mb-4">Ganti Password (Opsional)</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Password Saat Ini</label>
                                    <input type="password" name="current_password"
                                        class="w-full px-4 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 transition-all font-bold text-slate-700 text-sm"
                                        placeholder="Konfirmasi password lama">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Password Baru</label>
                                        <input type="password" name="new_password"
                                            class="w-full px-4 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 transition-all font-bold text-slate-700 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ulangi</label>
                                        <input type="password" name="new_password_confirmation"
                                            class="w-full px-4 py-3.5 rounded-2xl border border-slate-100 bg-slate-50 focus:outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-100 transition-all font-bold text-slate-700 text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 flex gap-3">
                        <button type="button" onclick="closeModal()" class="flex-1 px-6 py-4 bg-slate-50 hover:bg-slate-100 text-slate-500 font-black rounded-2xl transition text-sm">
                            Batal
                        </button>
                        <button type="submit" class="flex-[2] px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl transition-all shadow-xl shadow-emerald-500/20 active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modal-edit').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modal-edit').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on overlay click
    document.getElementById('modal-overlay').onclick = closeModal;
</script>

@endsection
