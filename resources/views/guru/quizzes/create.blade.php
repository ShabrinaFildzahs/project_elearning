@extends('layouts.app')
@section('title', 'Buat Kuis Baru')
@section('page_title', 'Buat Kuis Pilihan Ganda')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('guru.quizzes.store') }}" method="POST" id="quiz-form">
        @csrf
        <div class="space-y-8">
            
            {{-- Header Info --}}
            <div class="glass-card rounded-2xl p-8 border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="w-2 h-8 bg-emerald-500 rounded-full"></span>
                    Informasi Kuis
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Mata Pelajaran & Kelas</label>
                        <select name="id_pemetaan_akademik" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all font-bold text-slate-700">
                            <option value="">-- Pilih Kelas & Mapel --</option>
                            @foreach($pemetaan as $p)
                                <option value="{{ $p->id }}">{{ $p->mataPelajaran->nama }} - {{ $p->kelas->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Judul Kuis</label>
                        <input type="text" name="judul" required placeholder="Contoh: Kuis Harian Jaringan Dasar" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Tenggat Waktu</label>
                        <input type="datetime-local" name="tenggat_waktu" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Durasi (Menit)</label>
                        <input type="number" name="durasi_menit" value="60" required min="1" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all font-bold text-slate-700">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Deskripsi / Instruksi</label>
                        <textarea name="deskripsi" rows="3" placeholder="Tulis instruksi kuis di sini..." class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all font-bold text-slate-700"></textarea>
                    </div>
                </div>
            </div>

            {{-- Questions Section --}}
            <div id="questions-container" class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 flex items-center justify-between">
                    <span>Daftar Pertanyaan</span>
                    <button type="button" onclick="addQuestion()" class="text-xs px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Tambah Soal
                    </button>
                </h3>

                {{-- Question Item Template --}}
                <div class="question-item glass-card rounded-2xl p-8 border border-slate-100 bg-white shadow-sm animate-in fade-in slide-in-from-bottom-4" data-index="0">
                    <div class="flex justify-between items-start mb-6">
                        <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-black text-lg">1</span>
                        <button type="button" onclick="removeQuestion(this)" class="text-slate-300 hover:text-red-500 transition remove-btn hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Pertanyaan</label>
                            <textarea name="pertanyaan[]" required rows="2" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all font-bold text-slate-700 text-sm" placeholder="Tuliskan soal di sini..."></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center font-bold shrink-0">A</span>
                                <input type="text" name="opsi_a[]" required placeholder="Opsi A" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all text-sm font-semibold text-slate-600">
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center font-bold shrink-0">B</span>
                                <input type="text" name="opsi_b[]" required placeholder="Opsi B" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all text-sm font-semibold text-slate-600">
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center font-bold shrink-0">C</span>
                                <input type="text" name="opsi_c[]" required placeholder="Opsi C" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all text-sm font-semibold text-slate-600">
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center font-bold shrink-0">D</span>
                                <input type="text" name="opsi_d[]" required placeholder="Opsi D" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all text-sm font-semibold text-slate-600">
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center font-bold shrink-0">E</span>
                                <input type="text" name="opsi_e[]" required placeholder="Opsi E" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 transition-all text-sm font-semibold text-slate-600">
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold shrink-0">✔</span>
                                <select name="jawaban_benar[]" required class="w-full px-4 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50/30 focus:outline-none focus:border-emerald-500 transition-all text-sm font-bold text-emerald-700">
                                    <option value="">Pilih Jawaban Benar</option>
                                    <option value="A">Jawaban A</option>
                                    <option value="B">Jawaban B</option>
                                    <option value="C">Jawaban C</option>
                                    <option value="D">Jawaban D</option>
                                    <option value="E">Jawaban E</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold shrink-0">P</span>
                                <input type="number" name="poin[]" value="10" required min="0" placeholder="Poin Soal" class="w-full px-4 py-2.5 rounded-xl border border-amber-200 bg-amber-50/30 focus:outline-none focus:border-amber-500 transition-all text-sm font-bold text-amber-700">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center py-6">
                <a href="{{ route('guru.quizzes.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">Batal</a>
                <button type="submit" class="px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl transition shadow-xl shadow-emerald-500/20 active:scale-95">
                    Simpan & Terbitkan Kuis
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    let questionCount = 1;

    function addQuestion() {
        const container = document.getElementById('questions-container');
        const firstItem = document.querySelector('.question-item');
        const newItem = firstItem.cloneNode(true);
        
        questionCount++;
        
        // Reset values
        newItem.querySelectorAll('textarea, input').forEach(el => el.value = '');
        newItem.querySelectorAll('select').forEach(el => el.selectedIndex = 0);
        newItem.querySelector('.w-10').innerText = questionCount;
        newItem.querySelector('.remove-btn').classList.remove('hidden');
        
        newItem.classList.add('animate-in', 'fade-in', 'slide-in-from-bottom-4');
        container.appendChild(newItem);
        
        updateRemoveButtons();
    }

    function removeQuestion(btn) {
        const item = btn.closest('.question-item');
        item.remove();
        questionCount--;
        
        // Renumber remaining questions
        document.querySelectorAll('.question-item').forEach((el, index) => {
            el.querySelector('.w-10').innerText = index + 1;
        });
        
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.question-item');
        items.forEach((item, index) => {
            const btn = item.querySelector('.remove-btn');
            if (items.length === 1) {
                btn.classList.add('hidden');
            } else {
                btn.classList.remove('hidden');
            }
        });
    }
</script>
@endsection
