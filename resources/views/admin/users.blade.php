@extends('layouts.app')
@section('title', 'Kelola User')
@section('page_title', 'Kelola User')

@section('content')
{{-- Alert --}}
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium flex items-center space-x-2">
    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span>{{ session('success') }}</span>
</div>
@endif

<div class="flex items-center justify-between mb-6">
    <div>
        <h3 class="text-lg font-bold text-slate-800">Daftar Pengguna</h3>
        <p class="text-sm text-slate-500 mt-0.5">Total {{ $users->total() }} pengguna terdaftar</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="flex items-center space-x-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition shadow-lg shadow-blue-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah User</span>
    </a>
</div>

<div class="glass-card rounded-2xl overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Peran</th>
                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($users as $user)
            <tr class="hover:bg-slate-50/50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white {{ $user->role === 'guru' ? 'bg-emerald-600' : 'bg-purple-600' }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold uppercase
                        {{ $user->role === 'guru' ? 'bg-emerald-100 text-emerald-700' : 'bg-purple-100 text-purple-700' }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="px-6 py-4 flex items-center space-x-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition" title="Hapus">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400">Belum ada user terdaftar.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">{{ $users->links() }}</div>
    @endif
</div>
@endsection
