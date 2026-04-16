@extends('layouts.app')
@section('title', 'Kelola ' . ucfirst($role))
@section('page_title', 'Kelola Data ' . ucfirst($role))

@section('content')
{{-- Alert --}}
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium flex items-center space-x-2">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div class="flex bg-slate-200/50 p-1 rounded-xl w-fit">
        <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $role === 'guru' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Daftar Guru
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-bold transition {{ $role === 'siswa' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            Daftar Siswa
        </a>
    </div>
    
    <a href="{{ route('admin.users.create', ['role' => $role]) }}" class="flex items-center justify-center space-x-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah {{ ucfirst($role) }} Baruu</span>
    </a>
</div>

<div class="glass-card rounded-2xl overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email / Akun</th>
                @if($role === 'guru')
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Pendidikan</th>
                @else
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">NISN / Kelas</th>
                @endif
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($data as $item)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white {{ $role === 'guru' ? 'bg-emerald-600' : 'bg-purple-600' }}">
                            {{ strtoupper(substr($role === 'guru' ? $item->nama : $item->nama_lengkap, 0, 1)) }}
                        </div>
                        <div>
                            <span class="font-semibold text-slate-800">{{ $role === 'guru' ? $item->nama : $item->nama_lengkap }}</span>
                            <p class="text-[10px] text-slate-400 capitalize">{{ $item->jenis_kelamin }} · {{ $item->no_hp }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ $item->email }}</td>
                <td class="px-6 py-4">
                    @if($role === 'guru')
                        <span class="text-xs font-medium text-slate-700 bg-slate-100 px-2 py-1 rounded">{{ $item->pendidikan_terakhir }}</span>
                    @else
                        <div class="text-xs">
                            <span class="font-bold text-slate-700">{{ $item->nisn }}</span>
                            <p class="text-slate-400 mt-0.5">{{ $item->kelas->nama ?? 'Tanpa Kelas' }} ({{ $item->tahun_masuk }})</p>
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 flex items-center space-x-2">
                    <a href="{{ route('admin.users.edit', ['user' => $item->id, 'role' => $role]) }}"
                       class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form action="{{ route('admin.users.destroy', ['user' => $item->id, 'role' => $role]) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                        @csrf @method('DELETE')
                        <input type="hidden" name="role" value="{{ $role }}">
                        <button type="submit" class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada data {{ $role }} terdaftar.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($data->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">{{ $data->links() }}</div>
    @endif
</div>
@endsection
