@extends('layouts.app')
@section('title', ($user ? 'Edit ' : 'Tambah ') . ucfirst($role))
@section('page_title', ($user ? 'Edit ' : 'Tambah ') . ucfirst($role))

@section('content')
<div class="max-w-4xl">
    <div class="glass-card rounded-2xl p-8">
        <form action="{{ $user ? route('admin.users.update', ['user' => $user->id, 'role' => $role]) : route('admin.users.store', ['role' => $role]) }}" method="POST">
            @csrf
            @if($user) @method('PUT') @endif
            <input type="hidden" name="role" value="{{ $role }}">

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Data Akun --}}
                <div class="md:col-span-2">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        Informasi Akun
                    </h3>
                </div>

                @if($role === 'admin')
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user?->username) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email Login</label>
                    <input type="email" name="email" value="{{ old('email', $user?->email) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">
                        Password {{ $user ? '(Kosongkan jika tidak diubah)' : '' }}
                    </label>
                    <input type="password" name="password" {{ $user ? '' : 'required' }}
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                </div>

                {{-- Data Profil --}}
                <div class="md:col-span-2 pt-4">
                    <h3 class="text-sm font-bold text-blue-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        Profil Pribadi
                    </h3>
                </div>

                @if($role === 'guru')
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama" value="{{ old('nama', $user?->nama) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" value="{{ old('pendidikan_terakhir', $user?->pendidikan_terakhir) }}" required placeholder="Contoh: S1 Pendidikan Komputer"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                    </div>
                @elseif($role === 'siswa')
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap (Sesuai Ijazah)</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user?->nama_lengkap) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn', $user?->nisn) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Kelas Saat Ini</label>
                        <select name="id_kelas" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('id_kelas', $user?->id_kelas) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tahun Masuk</label>
                        <input type="number" name="tahun_masuk" value="{{ old('tahun_masuk', $user?->tahun_masuk ?? date('Y')) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                        <option value="L" {{ old('jenis_kelamin', $user?->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $user?->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $user?->tempat_lahir) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user?->tanggal_lahir) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">No. HP / WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user?->no_hp) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" required rows="3"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 transition text-sm">{{ old('alamat', $user?->alamat) }}</textarea>
                </div>
            </div>

            <div class="flex items-center space-x-3 mt-8">
                <button type="submit" class="px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-200">
                    {{ $user ? 'Simpan Perubahan' : 'Tambah Data ' . ucfirst($role) }}
                </button>
                <a href="{{ route('admin.users.index', ['role' => $role]) }}" class="px-8 py-3.5 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold rounded-xl transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
