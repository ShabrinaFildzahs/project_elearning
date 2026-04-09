<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - SMK Binaa</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .sidebar { background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); }
        .nav-active { background: rgba(59,130,246,0.15); border-left: 3px solid #3b82f6; color: #93c5fd; }
        .nav-inactive { border-left: 3px solid transparent; }
        .glass-card {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(203,213,225,0.5);
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
        }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 flex h-screen overflow-hidden">

    @php $role = auth()->user()->role ?? 'siswa'; $user = auth()->user(); @endphp

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar w-64 flex-shrink-0 flex flex-col h-full text-white">
        {{-- Logo --}}
        <div class="p-6 border-b border-white/5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center shrink-0 shadow-lg shadow-blue-600/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm-4 9.4v3.1L12 18l4-2.5v-3.1L12 15l-4-2.6z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-white leading-none">SMK BINAA</p>
                    <p class="text-[10px] text-slate-400 mt-0.5 uppercase tracking-widest">E-Learning</p>
                </div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="p-4 mx-3 mt-4 rounded-xl bg-white/5 border border-white/10 flex items-center space-x-3">
            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm shrink-0
                @if($role=='admin') bg-blue-600 @elseif($role=='guru') bg-emerald-600 @else bg-purple-600 @endif">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-white truncate">{{ $user->name ?? 'User' }}</p>
                <p class="text-[10px] text-slate-400 capitalize">{{ $role }}</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 p-3 mt-4 space-y-1 overflow-y-auto">
            @if($role == 'admin')
                <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium nav-active">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/users" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Kelola User</span>
                </a>
                <a href="/admin/classes" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Kelas & Mapel</span>
                </a>
                <a href="/admin/schedules" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Jadwal</span>
                </a>

            @elseif($role == 'guru')
                <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium nav-active">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="/guru/schedules" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Jadwal Mengajar</span>
                </a>
                <a href="/guru/materials" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Upload Materi</span>
                </a>
                <a href="/guru/assignments" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>Tugas & Kuis</span>
                </a>
                <a href="/guru/forums" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                    <span>Forum Diskusi</span>
                </a>

            @else {{-- siswa --}}
                <a href="/dashboard" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium nav-active">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="/siswa/schedules" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Jadwal Pelajaran</span>
                </a>
                <a href="/siswa/materials" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Materi Pelajaran</span>
                </a>
                <a href="/siswa/assignments" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>Tugas & Kuis</span>
                </a>
                <a href="/siswa/forums" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                    <span>Forum Diskusi</span>
                </a>
            @endif
        </nav>

        {{-- Logout --}}
        <div class="p-3 border-t border-white/5">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition nav-inactive">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        {{-- Topbar --}}
        <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-8 shrink-0">
            <h2 class="text-base font-bold text-slate-800">@yield('page_title', 'Dashboard')</h2>
            <div class="flex items-center space-x-3">
                @if($role == 'siswa')
                    <div class="hidden md:flex flex-col items-end mr-2">
                        <span class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Progres Belajar</span>
                        <div class="flex items-center space-x-2 mt-1">
                            <div class="w-32 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500 rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-xs font-bold text-blue-600">75%</span>
                        </div>
                    </div>
                @endif
                <div class="flex items-center space-x-2 bg-slate-50 border border-slate-200 rounded-full pl-3 pr-1 py-1">
                    <span class="text-sm font-medium text-slate-700">{{ $user->name ?? 'User' }}</span>
                    <div class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs text-white
                        @if($role=='admin') bg-blue-600 @elseif($role=='guru') bg-emerald-600 @else bg-purple-600 @endif">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-8 bg-slate-50">
            @yield('content')
        </main>
    </div>

</body>
</html>
