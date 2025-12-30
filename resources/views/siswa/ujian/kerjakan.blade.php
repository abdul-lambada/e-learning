@extends('layouts.siswa_mobile')

@section('title', 'Ujian Berlangsung')

@section('content')
    <style>
        /* Force hidden navbar and tabs for true Focus Mode */
        header,
        nav {
            display: none !important;
        }

        body {
            padding-bottom: 0 !important;
        }

        main {
            padding: 0 !important;
        }

        .question-card {
            display: none;
        }

        .question-card.active {
            display: block;
        }

        .option-checked {
            border-color: #4f46e5;
            background-color: #f5f3ff;
        }

        .nav-answered {
            border-color: #4f46e5;
            color: #4f46e5;
            background-color: #eef2ff;
        }

        .nav-current {
            background-color: #4f46e5;
            color: #ffffff;
            border-color: #4f46e5;
        }
    </style>

    <div class="flex flex-col h-screen bg-gray-50">
        <!-- Sticky Exam Header -->
        <header class="bg-white border-b border-gray-100 shrink-0 z-50">
            <div class="px-5 py-3 flex justify-between items-center">
                <div class="min-w-0 flex-1">
                    <h1 class="text-sm font-black text-gray-900 leading-tight truncate">
                        {{ $jawabanUjian->ujian->nama_ujian }}</h1>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Siswa:
                        {{ explode(' ', auth()->user()->nama_lengkap)[0] }}</p>
                </div>
                <div class="text-right pl-4">
                    <span class="text-[8px] font-bold text-gray-400 uppercase block tracking-tighter">Sisa Waktu</span>
                    <span id="timer-display" class="text-lg font-black text-indigo-600 tabular-nums">--:--:--</span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="h-1 w-full bg-gray-100">
                <div id="exam-progress" class="h-full bg-indigo-600 transition-all duration-500" style="width: 0%"></div>
            </div>
        </header>

        <!-- Question Body -->
        <main class="flex-1 overflow-y-auto hide-scrollbar p-6 pb-32">
            @foreach ($jawabanUjian->detailJawaban as $index => $detail)
                <div class="question-card space-y-6 animate-in slide-in-from-right duration-300"
                    id="q-card-{{ $index }}" data-index="{{ $index }}" data-detail-id="{{ $detail->id }}">

                    <div class="flex items-center justify-between px-1">
                        <span
                            class="inline-block px-3 py-1 bg-white border border-gray-100 rounded-full text-[10px] font-bold text-indigo-600 shadow-sm">
                            Soal {{ $loop->iteration }} / {{ $jawabanUjian->detailJawaban->count() }}
                        </span>
                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Bobot:
                            {{ $detail->soalUjian->bobot_nilai }}</span>
                    </div>

                    <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 relative overflow-hidden">
                        <div class="prose prose-sm prose-slate max-w-none text-gray-800 font-medium leading-relaxed">
                            {!! nl2br(e($detail->soalUjian->pertanyaan)) !!}
                        </div>

                        @if ($detail->soalUjian->gambar_soal)
                            <div class="mt-4 rounded-2xl overflow-hidden ring-1 ring-gray-100">
                                <img src="{{ Storage::url($detail->soalUjian->gambar_soal) }}"
                                    class="w-full object-contain max-h-64">
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3">
                        @if ($detail->soalUjian->tipe_soal == 'pilihan_ganda')
                            @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                                @if ($detail->soalUjian->{'pilihan_' . strtolower($opt)})
                                    <label class="block relative active:scale-[0.98] transition-all cursor-pointer">
                                        <input type="radio" name="ans_{{ $detail->id }}" value="{{ $opt }}"
                                            class="peer hidden" {{ $detail->jawaban_dipilih == $opt ? 'checked' : '' }}
                                            onchange="saveAnswer({{ $index }}, '{{ $opt }}')">
                                        <div
                                            class="p-5 bg-white border-2 border-transparent shadow-sm rounded-3xl flex items-center gap-4 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all ">
                                            <div
                                                class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center text-xs font-black text-gray-400 peer-checked:bg-indigo-600 peer-checked:text-white shrink-0">
                                                {{ $opt }}
                                            </div>
                                            <span
                                                class="text-sm font-bold text-gray-700 leading-tight">{{ $detail->soalUjian->{'pilihan_' . strtolower($opt)} }}</span>
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                        @else
                            <div class="bg-white p-6 rounded-[32px] border border-gray-100 shadow-sm">
                                <span
                                    class="text-[9px] font-bold text-gray-400 uppercase tracking-widest block mb-3 pl-1">Jawaban
                                    Anda</span>
                                <textarea onblur="saveAnswer({{ $index }}, this.value)" placeholder="Tuliskan jawaban essay Anda di sini..."
                                    class="w-full bg-gray-50 rounded-2xl p-4 border-0 focus:ring-2 focus:ring-indigo-500 text-sm font-medium min-h-[160px] resize-none outline-none leading-relaxed">{{ $detail->jawaban_essay }}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </main>

        <!-- Bottom Controls -->
        <footer class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-md border-t border-gray-100 p-5 z-50">
            <div class="flex items-center gap-3 max-w-lg mx-auto">
                <button onclick="navStep(-1)" id="btn-prev"
                    class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 active:scale-90 transition-transform disabled:opacity-50">
                    <i class='bx bx-chevron-left text-3xl'></i>
                </button>

                <button onclick="toggleNav()"
                    class="flex-1 h-14 bg-indigo-50 text-indigo-600 rounded-2xl font-black text-xs uppercase tracking-widest active:scale-95 transition-all">
                    NAVIGASI SOAL
                </button>

                <button onclick="navStep(1)" id="btn-next"
                    class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100 active:scale-90 transition-transform disabled:opacity-50">
                    <i class='bx bx-chevron-right text-3xl'></i>
                </button>
            </div>
        </footer>

        <!-- Navigation Sheet (Sidebar replacement for Mobile) -->
        <div id="nav-sheet" class="hidden fixed inset-0 z-100 p-6 items-end justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-white w-full rounded-[40px] p-8 space-y-6 animate-in slide-in-from-bottom duration-300">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-black text-gray-900">Daftar Soal</h3>
                    <button onclick="toggleNav()"
                        class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400"><i
                            class='bx bx-x text-2xl'></i></button>
                </div>

                <div class="grid grid-cols-5 gap-3 h-80 overflow-y-auto hide-scrollbar p-1">
                    @foreach ($jawabanUjian->detailJawaban as $index => $detail)
                        <button id="nav-btn-{{ $index }}" onclick="goToQuestion({{ $index }})"
                            class="h-12 border-2 border-gray-100 rounded-xl flex items-center justify-center text-sm font-black text-gray-400 transition-all
                                {{ $detail->jawaban_dipilih || $detail->jawaban_essay ? 'nav-answered' : '' }}">
                            {{ $loop->iteration }}
                        </button>
                    @endforeach
                </div>

                <form action="{{ route('siswa.ujian.finish', $jawabanUjian->id) }}" method="POST" id="form-finish">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Akhiri ujian sekarang? Pastikan semua soal telah terjawab.')"
                        class="w-full bg-red-500 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-red-100 active:scale-95 transition-all">
                        SELESAI UJIAN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const total = {{ $jawabanUjian->detailJawaban->count() }};
        const endTime = new Date("{{ $endTime->toIso8601String() }}").getTime();
        let currentIndex = 0;

        // Timer Logic
        const timer = setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(timer);
                alert('Waktu habis! Jawaban Anda akan otomatis terkirim.');
                document.getElementById('form-finish').submit();
                return;
            }

            const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const s = Math.floor((distance % (1000 * 60)) / 1000);

            const display =
                `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            document.getElementById('timer-display').innerText = display;

            if (distance < 300000) { // 5 minutes warning
                document.getElementById('timer-display').classList.add('text-red-500', 'animate-pulse');
            }
        }, 1000);

        function showQuestion(index) {
            if (index < 0 || index >= total) return;

            currentIndex = index;
            document.querySelectorAll('.question-card').forEach(c => c.classList.remove('active'));
            document.querySelectorAll('.nav-num-btn').forEach(b => b.classList.remove('nav-current')); // Legacy ref
            document.querySelectorAll("[id^='nav-btn-']").forEach(b => b.classList.remove('nav-current'));

            document.getElementById('q-card-' + index).classList.add('active');
            document.getElementById('nav-btn-' + index).classList.add('nav-current');

            document.getElementById('btn-prev').disabled = index === 0;
            document.getElementById('btn-next').disabled = index === total - 1;

            // Progress Bar
            const prog = ((index + 1) / total) * 100;
            document.getElementById('exam-progress').style.width = prog + '%';

            window.scrollTo(0, 0);
        }

        function navStep(s) {
            showQuestion(currentIndex + s);
        }

        function goToQuestion(i) {
            showQuestion(i);
            toggleNav();
        }

        function toggleNav() {
            const sheet = document.getElementById('nav-sheet');
            sheet.classList.toggle('hidden');
            sheet.classList.toggle('flex');
        }

        function saveAnswer(index, val) {
            const detailId = document.getElementById('q-card-' + index).getAttribute('data-detail-id');
            const navBtn = document.getElementById('nav-btn-' + index);

            $.post("{{ route('siswa.ujian.simpan') }}", {
                _token: "{{ csrf_token() }}",
                detail_id: detailId,
                jawaban: val
            }).done(() => {
                if (val && val.trim() !== '') navBtn.classList.add('nav-answered');
                else navBtn.classList.remove('nav-answered');
            });
        }

        document.addEventListener('DOMContentLoaded', () => showQuestion(0));
    </script>
@endsection
