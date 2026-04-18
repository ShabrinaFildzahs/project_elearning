@extends('layouts.guest')

@section('title', 'Login - E-Learning SMK Mitra Bintaro')

@section('content')
    <div class="w-full max-w-md">

        {{-- Logo & Header --}}
        <div class="text-center mb-10">
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 mb-4 shadow-lg shadow-blue-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm-4 9.4v3.1L12 18l4-2.5v-3.1L12 15l-4-2.6z" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">SMK <span class="text-blue-400">Mitra
                    Bintaro</span></h1>
            <p class="text-slate-400 mt-2 text-sm">Portal E-Learning Terpadu</p>

            {{-- Role Badges --}}
            <div class="flex items-center justify-center gap-2 mt-4">
                <span
                    class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/30">Admin</span>
                <span class="text-slate-600">·</span>
                <span
                    class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Guru</span>
                <span class="text-slate-600">·</span>
                <span
                    class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-500/20 text-purple-400 border border-purple-500/30">Siswa</span>
            </div>
        </div>

        {{-- Login Card --}}
        <div class="glass-card rounded-3xl p-8 shadow-2xl">
            <h2 class="text-xl font-bold text-white mb-6">Masuk ke Akun Anda</h2>

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                    <strong>Gagal Masuk:</strong> {{ $errors->first('login') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Login Identity (Email or Username) --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Username atau
                        Email</label>
                    <input type="text" name="login" value="{{ old('login') }}"
                        placeholder="Username untuk Admin / Email untuk Guru & Siswa"
                        class="w-full px-4 py-3.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                        required autofocus>
                </div>

                {{-- Password --}}
                <div>
                    <label
                        class="block text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Password</label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full px-4 py-3.5 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                        required>
                </div>

                {{-- Login Button --}}
                <button type="submit"
                    class="w-full py-4 mt-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-base transition-all shadow-lg shadow-blue-600/30 hover:shadow-blue-500/40 hover:-translate-y-0.5 active:translate-y-0">
                    Masuk Sekarang →
                </button>
            </form>


        </div>

        <p class="text-center text-xs text-slate-600 mt-6">
            Kesulitan masuk? Hubungi <span class="text-slate-400 font-medium">IT Support Sekolah</span>
        </p>
    </div>
@endsection