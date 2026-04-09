@extends('layouts.app')
@section('title', $user ? 'Edit User' : 'Tambah User')
@section('page_title', $user ? 'Edit User' : 'Tambah User Baru')

@section('content')
<div class="max-w-2xl">
    <div class="glass-card rounded-2xl p-8">
        <form action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST">
            @csrf
            @if($user) @method('PUT') @endif

            @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user?->name) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user?->email) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">
                        Password {{ $user ? '(Kosongkan jika tidak diubah)' : '' }}
                    </label>
                    <input type="password" name="password" {{ $user ? '' : 'required' }}
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Peran</label>
                    <select name="role" required class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition text-sm">
                        <option value="guru" {{ old('role', $user?->role) === 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="siswa" {{ old('role', $user?->role) === 'siswa' ? 'selected' : '' }}>Siswa</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-3 mt-8">
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-200">
                    {{ $user ? 'Simpan Perubahan' : 'Tambah User' }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold rounded-xl transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
