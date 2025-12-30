<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian: {{ $jawabanKuis->kuis->judul_kuis }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/siswa.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .answer-btn:checked+label {
            @apply border-indigo-600 bg-indigo-50 ring-2 ring-indigo-100 ring-offset-0;
        }

        .answer-btn:checked+label .option-badge {
            @apply bg-indigo-600 text-white border-indigo-600;
        }

        .answer-btn:checked+label .option-text {
            @apply text-indigo-900 font-bold;
        }
    </style>
</head>

<body class="h-full overflow-hidden flex flex-col">

    <!-- Header Focus Mode -->
    <nav
        class="bg-white/80 backdrop-blur-md px-4 py-3 border-b border-slate-100 flex justify-between items-center shrink-0 z-50">
        <div class="flex flex-col">
            <h1 class="text-xs font-bold text-slate-400 uppercase tracking-widest line-clamp-1">
                {{ $jawabanKuis->kuis->judul_kuis }}</h1>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-sm font-bold text-slate-800" id="current-pos">Soal 1 /
                    {{ $jawabanKuis->detailJawaban->count() }}</span>
            </div>
        </div>

        <div class="text-center bg-red-50 px-4 py-1.5 rounded-2xl border border-red-100">
            <span class="block text-[8px] font-black text-red-400 uppercase tracking-tighter">Sisa Waktu</span>
            <span class="text-base font-black text-red-600 tabular-nums leading-none" id="timer-display">00:00:00</span>
        </div>
    </nav>

    <!-- Main Question Area -->
    <main class="flex-1 overflow-y-auto p-4 hide-scrollbar space-y-6">
        @foreach ($jawabanKuis->detailJawaban as $index => $detail)
            <div id="q-card-{{ $index }}"
                class="question-page space-y-6 hidden animate-in fade-in slide-in-from-right-4 duration-300"
                data-detail-id="{{ $detail->id }}">
                <!-- Question Content -->
                <div class="bg-white rounded-[32px] p-6 shadow-sm border border-slate-100 space-y-4">
                    <div class="flex justify-between items-center px-1">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pertanyaan</span>
                        <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">Bobot:
                            {{ $detail->soalKuis->bobot_nilai }}</span>
                    </div>

                    <div class="text-slate-800 text-lg font-medium leading-relaxed">
                        {!! nl2br(e($detail->soalKuis->pertanyaan)) !!}
                    </div>

                    @if ($detail->soalKuis->gambar_soal)
                        <div class="rounded-2xl overflow-hidden border border-slate-100">
                            <img src="{{ Storage::url($detail->soalKuis->gambar_soal) }}"
                                class="w-full h-auto object-contain bg-slate-50" alt="Gambar Soal">
                        </div>
                    @endif
                </div>

                <!-- Answer Options -->
                <div class="space-y-3">
                    @if ($detail->soalKuis->tipe_soal == 'pilihan_ganda')
                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                            @if ($detail->soalKuis->{'pilihan_' . strtolower($opt)})
                                <div class="relative">
                                    <input type="radio" name="ans_{{ $detail->id }}"
                                        id="opt_{{ $detail->id }}_{{ $opt }}" class="answer-btn hidden"
                                        value="{{ $opt }}"
                                        {{ $detail->jawaban_dipilih == $opt ? 'checked' : '' }}
                                        onchange="saveAnswer({{ $index }}, '{{ $opt }}')">
                                    <label for="opt_{{ $detail->id }}_{{ $opt }}"
                                        class="flex items-center gap-4 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm active:scale-[0.98] transition-all cursor-pointer">
                                        <span
                                            class="option-badge w-8 h-8 rounded-xl border border-slate-200 flex items-center justify-center font-bold text-sm text-slate-400 shrink-0 transition-colors">
                                            {{ $opt }}
                                        </span>
                                        <span
                                            class="option-text text-sm text-slate-600 transition-all leading-tight">{{ $detail->soalKuis->{'pilihan_' . strtolower($opt)} }}</span>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <div class="bg-white rounded-3xl p-4 border border-slate-200 shadow-sm">
                            <label
                                class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-2 block mb-2">Tulis
                                Jawaban Essay</label>
                            <textarea
                                class="w-full bg-slate-50 rounded-2xl p-4 border-0 text-slate-800 font-medium text-sm focus:ring-2 focus:ring-indigo-500 outline-none min-h-[160px]"
                                placeholder="Ketik jawaban di sini..." onblur="saveAnswer({{ $index }}, this.value)">{{ $detail->jawaban_essay }}</textarea>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </main>

    <!-- Bottom Navigation Controls -->
    <div
        class="bg-white p-4 border-t border-slate-100 shrink-0 space-y-4 shadow-[0_-10px_20px_-5px_rgba(0,0,0,0.05)] z-40">
        <div class="flex items-center gap-3">
            <button id="btn-prev" onclick="navStep(-1)"
                class="flex-1 py-4 px-6 rounded-2xl bg-slate-100 text-slate-500 font-bold text-sm flex items-center justify-center gap-2 active:scale-95 transition-all disabled:opacity-50">
                <i class='bx bx-chevron-left text-xl'></i>
                Kembali
            </button>

            <button onclick="toggleNavPanel()"
                class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 group">
                <i class='bx bxs-grid-alt text-2xl group-active:scale-90 transition-transform'></i>
            </button>

            <button id="btn-next" onclick="navStep(1)"
                class="flex-1 py-4 px-6 rounded-2xl bg-indigo-600 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                Lanjut
                <i class='bx bx-chevron-right text-xl'></i>
            </button>
        </div>
    </div>

    <!-- Slide-up Question Navigation -->
    <div id="nav-panel" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="toggleNavPanel()"></div>
        <div
            class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[40px] p-8 space-y-6 animate-in slide-in-from-bottom duration-300">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-2"></div>

            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Navigasi Soal</h3>
                <form action="{{ route('siswa.kuis.finish', $jawabanKuis->id) }}" method="POST" id="form-finish">
                    @csrf
                    <button type="submit" onclick="return confirm('Kumpulkan semua jawaban dan akhiri ujian?')"
                        class="bg-red-500 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg shadow-red-100">Selesai</button>
                </form>
            </div>

            <div class="grid grid-cols-5 gap-3 max-h-[40vh] overflow-y-auto hide-scrollbar p-1">
                @foreach ($jawabanKuis->detailJawaban as $index => $detail)
                    <button id="nav-btn-{{ $index }}" onclick="goToQuestion({{ $index }})"
                        class="w-full aspect-square rounded-xl border-2 flex items-center justify-center font-bold transition-all
                            {{ $detail->jawaban_dipilih || $detail->jawaban_essay ? 'bg-indigo-600 border-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-white border-slate-100 text-slate-400' }}">
                        {{ $loop->iteration }}
                    </button>
                @endforeach
            </div>

            <div class="pt-2 flex justify-center gap-4 text-[10px] font-bold text-slate-400">
                <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-indigo-600"></span> Dijawab
                </div>
                <div class="flex items-center gap-1.5"><span
                        class="w-2 h-2 rounded-full bg-white border border-slate-200"></span> Belum</div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const total = {{ $jawabanKuis->detailJawaban->count() }};
        const endTime = new Date("{{ $endTime->toIso8601String() }}").getTime();
        let current = 0;

        // Timer
        const interval = setInterval(() => {
            const now = new Date().getTime();
            const dist = endTime - now;
            if (dist < 0) {
                clearInterval(interval);
                alert('Waktu Habis!');
                document.getElementById('form-finish').submit();
                return;
            }
            const h = Math.floor(dist / (1000 * 60 * 60));
            const m = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((dist % (1000 * 60)) / 1000);
            document.getElementById('timer-display').innerText =
                `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
        }, 1000);

        function showQ(idx) {
            document.querySelectorAll('.question-page').forEach(p => p.classList.add('hidden'));
            document.getElementById('q-card-' + idx).classList.remove('hidden');
            document.getElementById('current-pos').innerText = `Soal ${idx + 1} / ${total}`;

            document.getElementById('btn-prev').disabled = idx === 0;
            const btnNext = document.getElementById('btn-next');
            if (idx === total - 1) {
                btnNext.innerHTML = "Selesai <i class='bx bx-check-double text-xl'></i>";
                btnNext.classList.replace('bg-indigo-600', 'bg-green-600');
            } else {
                btnNext.innerHTML = "Lanjut <i class='bx bx-chevron-right text-xl'></i>";
                btnNext.classList.replace('bg-green-600', 'bg-indigo-600');
            }
            current = idx;
        }

        function navStep(step) {
            if (current === total - 1 && step > 0) {
                toggleNavPanel();
                return;
            }
            const next = current + step;
            if (next >= 0 && next < total) showQ(next);
        }

        function goToQuestion(idx) {
            showQ(idx);
            toggleNavPanel();
        }

        function toggleNavPanel() {
            const el = document.getElementById('nav-panel');
            el.classList.toggle('hidden');
        }

        function saveAnswer(idx, ans) {
            const card = document.getElementById('q-card-' + idx);
            const id = card.getAttribute('data-detail-id');
            const navBtn = document.getElementById('nav-btn-' + idx);

            $.post("{{ route('siswa.kuis.simpan') }}", {
                _token: "{{ csrf_token() }}",
                detail_id: id,
                jawaban: ans
            }).done(() => {
                if (ans && ans.trim() !== '') {
                    navBtn.classList.replace('bg-white', 'bg-indigo-600');
                    navBtn.classList.replace('border-slate-100', 'border-indigo-600');
                    navBtn.classList.replace('text-slate-400', 'text-white');
                    navBtn.classList.add('shadow-md', 'shadow-indigo-100');
                } else {
                    navBtn.classList.replace('bg-indigo-600', 'bg-white');
                    navBtn.classList.replace('border-indigo-600', 'border-slate-100');
                    navBtn.classList.replace('text-white', 'text-slate-400');
                    navBtn.classList.remove('shadow-md', 'shadow-indigo-100');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => showQ(0));
    </script>
</body>

</html>
