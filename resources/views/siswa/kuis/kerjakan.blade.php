@extends('layouts.app')

@section('title', 'Ujian Berlangsung')

@section('content')
    <!-- Override container style for full focus -->
    <style>
        .layout-menu,
        .layout-navbar {
            display: none !important;
        }

        /* Hide Sidebar & Navbar */
        .content-wrapper {
            padding: 0 !important;
            margin: 0 !important;
        }

        .container-xxl {
            max-width: 100% !important;
            padding: 20px;
        }

        /* Exam Styles */
        .exam-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #fff;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }

        .exam-body {
            margin-top: 80px;
        }

        .question-card {
            display: none;
        }

        .question-card.active {
            display: block;
        }

        .nav-num-btn {
            width: 40px;
            height: 40px;
            margin: 5px;
            border-radius: 5px;
            font-weight: bold;
            border: 1px solid #ddd;
            background: #fff;
        }

        .nav-num-btn.active {
            border-color: #696cff;
            color: #696cff;
        }

        .nav-num-btn.answered {
            background-color: #e7e7ff;
            border-color: #696cff;
        }

        .nav-num-btn.current {
            background-color: #696cff;
            color: #fff;
            border-color: #696cff;
        }

        .option-label {
            display: block;
            padding: 10px 15px;
            border: 1px solid #d9dee3;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 10px;
        }

        .option-label:hover {
            background-color: #f5f5f9;
        }

        .option-input:checked+.option-label {
            background-color: #e7e7ff;
            border-color: #696cff;
            color: #696cff;
            font-weight: bold;
        }

        .option-input {
            display: none;
        }
    </style>

    <!-- Exam Header -->
    <div class="exam-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="m-0 fw-bold">{{ $jawabanKuis->kuis->judul_kuis }}</h5>
            <small class="text-muted">Percobaan ke-{{ $jawabanKuis->percobaan_ke }}</small>
        </div>
        <div class="text-center">
            <small class="text-muted display-block">Sisa Waktu</small>
            <h3 class="m-0 fw-bold text-danger" id="timer-display">--:--:--</h3>
        </div>
        <form action="{{ route('siswa.kuis.finish', $jawabanKuis->id) }}" method="POST" id="form-finish"
            onsubmit="return confirm('Apakah Anda yakin ingin mengakhiri ujian? Pastikan semua jawaban sudah terisi.')">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="bx bx-check-double me-1"></i> Selesai Ujian
            </button>
        </form>
    </div>

    <div class="container-xxl exam-body">
        <div class="row">
            <!-- Main Question Area -->
            <div class="col-md-9 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        @foreach ($jawabanKuis->detailJawaban as $index => $detail)
                            <div class="question-card" id="q-card-{{ $index }}" data-index="{{ $index }}"
                                data-detail-id="{{ $detail->id }}">
                                <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                                    <h5 class="mb-0">Soal No. {{ $loop->iteration }}</h5>
                                    <span class="badge bg-label-secondary">Bobot:
                                        {{ $detail->soalKuis->bobot_nilai }}</span>
                                </div>

                                <div class="mb-4 text-break">
                                    {!! nl2br(e($detail->soalKuis->pertanyaan)) !!}
                                </div>

                                @if ($detail->soalKuis->gambar_soal)
                                    <div class="mb-4 text-center">
                                        <img src="{{ Storage::url($detail->soalKuis->gambar_soal) }}"
                                            class="img-fluid rounded border" style="max-height: 300px;">
                                    </div>
                                @endif

                                <!-- Jawaban Area -->
                                <div class="answer-area">
                                    @if ($detail->soalKuis->tipe_soal == 'pilihan_ganda')
                                        @foreach (['A', 'B', 'C', 'D', 'E'] as $opt)
                                            @if ($detail->soalKuis->{'pilihan_' . strtolower($opt)})
                                                <input type="radio" name="ans_{{ $detail->id }}"
                                                    id="opt_{{ $detail->id }}_{{ $opt }}" class="option-input"
                                                    value="{{ $opt }}"
                                                    {{ $detail->jawaban_dipilih == $opt ? 'checked' : '' }}
                                                    onchange="saveAnswer({{ $index }}, '{{ $opt }}')">
                                                <label for="opt_{{ $detail->id }}_{{ $opt }}"
                                                    class="option-label">
                                                    <span class="badge bg-label-primary me-2">{{ $opt }}</span>
                                                    {{ $detail->soalKuis->{'pilihan_' . strtolower($opt)} }}
                                                </label>
                                            @endif
                                        @endforeach
                                    @else
                                        <textarea class="form-control" rows="5" placeholder="Tulis jawaban Anda di sini..."
                                            onblur="saveAnswer({{ $index }}, this.value)">{{ $detail->jawaban_essay }}</textarea>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-secondary" id="btn-prev" onclick="navStep(-1)" disabled>
                            <i class="bx bx-chevron-left me-1"></i> Sebelumnya
                        </button>
                        <button class="btn btn-primary" id="btn-next" onclick="navStep(1)">
                            Selanjutnya <i class="bx bx-chevron-right me-1"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation Sidebar -->
            <div class="col-md-3">
                <div class="card sticky-top" style="top: 100px;">
                    <div class="card-header border-bottom py-2">
                        <h6 class="mb-0">Navigasi Soal</h6>
                    </div>
                    <div class="card-body p-2 d-flex flex-wrap justify-content-center">
                        @foreach ($jawabanKuis->detailJawaban as $index => $detail)
                            <button
                                class="nav-num-btn {{ $detail->jawaban_dipilih || $detail->jawaban_essay ? 'answered' : '' }}"
                                id="nav-btn-{{ $index }}" onclick="goToQuestion({{ $index }})">
                                {{ $loop->iteration }}
                            </button>
                        @endforeach
                    </div>
                    <div class="card-footer text-center small text-muted">
                        <span class="badge bg-primary me-1"> </span> Sekarang
                        <span class="badge bg-label-primary mx-1"> </span> Dijawab
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Config
        const totalQuestions = {{ $jawabanKuis->detailJawaban->count() }};
        const endTime = new Date("{{ $endTime->toIso8601String() }}").getTime();
        let currentIndex = 0;

        // Timer Logic
        const timerInterval = setInterval(function() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                clearInterval(timerInterval);
                document.getElementById("timer-display").innerHTML = "WAKTU HABIS";
                alert('Waktu ujian telah habis! Jawaban akan dikumpulkan otomatis.');
                document.getElementById("form-finish").submit();
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("timer-display").innerHTML =
                (hours < 10 ? "0" + hours : hours) + ":" +
                (minutes < 10 ? "0" + minutes : minutes) + ":" +
                (seconds < 10 ? "0" + seconds : seconds);

            // Warning Colors
            if (distance < 300000) { // < 5 mins
                document.getElementById("timer-display").classList.add('text-danger');
                document.getElementById("timer-display").classList.add('blink');
            }
        }, 1000);

        // Navigation Logic
        function showQuestion(index) {
            // Validation bound
            if (index < 0 || index >= totalQuestions) return;

            currentIndex = index;

            // Hide all
            document.querySelectorAll('.question-card').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-num-btn').forEach(el => el.classList.remove('current'));

            // Show current
            const currentCard = document.getElementById('q-card-' + index);
            currentCard.classList.add('active');
            document.getElementById('nav-btn-' + index).classList.add('current');

            // Buttons state
            document.getElementById('btn-prev').disabled = index === 0;
            document.getElementById('btn-next').disabled = index === totalQuestions - 1;

            // Scroll to top
            window.scrollTo(0, 0);
        }

        function navStep(step) {
            showQuestion(currentIndex + step);
        }

        function goToQuestion(index) {
            showQuestion(index);
        }

        // Ajax Save Logic
        function saveAnswer(index, answer) {
            const card = document.getElementById('q-card-' + index);
            const detailId = card.getAttribute('data-detail-id');
            const navBtn = document.getElementById('nav-btn-' + index);

            // UI Feedback: Saving...
            // Can add spinner or something

            $.ajax({
                url: "{{ route('siswa.kuis.simpan') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    detail_id: detailId,
                    jawaban: answer
                },
                success: function(response) {
                    // Mark navigation as answered
                    if (answer && answer.trim() !== '') {
                        navBtn.classList.add('answered');
                    } else {
                        navBtn.classList.remove('answered');
                    }
                },
                error: function(xhr) {
                    console.error("Gagal menyimpan jawaban", xhr);
                    alert('Gagal menyimpan jawaban. Periksa koneksi internet Anda.');
                }
            });
        }

        // Init
        document.addEventListener('DOMContentLoaded', function() {
            showQuestion(0);
        });
    </script>

    <style>
        .blink {
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
@endsection
