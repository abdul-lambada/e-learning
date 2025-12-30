@extends('layouts.siswa_mobile')

@section('title', 'Scan QR Absensi')

@section('content')
    <div class="space-y-6 pb-20 mt-4">
        <!-- Header -->
        <div class="text-center space-y-2">
            <h2 class="text-2xl font-bold text-gray-900">Scan QR Code</h2>
            <p class="text-xs text-gray-500 font-medium px-8">Arahkan kamera HP kamu ke QR Code yang ditampilkan oleh Guru di
                depan kelas.</p>
        </div>

        <!-- Scanner Container -->
        <div
            class="relative mx-auto w-full aspect-square max-w-[320px] rounded-[48px] overflow-hidden border-8 border-white shadow-2xl shadow-indigo-100 bg-slate-900 group">
            <div id="reader" class="w-full h-full object-cover"></div>

            <!-- Scanning Animation Overlays -->
            <div
                class="absolute inset-x-8 top-1/2 -translate-y-1/2 h-0.5 bg-indigo-500 shadow-[0_0_15px_rgba(99,102,241,0.8)] animate-bounce z-10">
            </div>

            <!-- Corner Accents -->
            <div class="absolute top-8 left-8 w-8 h-8 border-t-4 border-l-4 border-white/50 rounded-tl-lg"></div>
            <div class="absolute top-8 right-8 w-8 h-8 border-t-4 border-r-4 border-white/50 rounded-tr-lg"></div>
            <div class="absolute bottom-8 left-8 w-8 h-8 border-b-4 border-l-4 border-white/50 rounded-bl-lg"></div>
            <div class="absolute bottom-8 right-8 w-8 h-8 border-b-4 border-r-4 border-white/50 rounded-br-lg"></div>
        </div>

        <!-- Feedback Area -->
        <div id="scan-feedback"
            class="hidden mx-auto max-w-[280px] p-4 rounded-3xl text-center space-y-2 animate-in fade-in zoom-in duration-300">
            <div id="feedback-icon" class="w-12 h-12 rounded-full mx-auto flex items-center justify-center text-2xl"></div>
            <h4 id="feedback-title" class="font-bold text-sm"></h4>
            <p id="feedback-msg" class="text-[10px] font-medium"></p>
        </div>

        <div class="flex justify-center pt-4">
            <button id="btn-toggle-camera" onclick="restartScanner()"
                class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-100 rounded-2xl text-xs font-bold text-gray-600 shadow-sm active:scale-95 transition-all">
                <i class='bx bx-refresh text-xl'></i>
                Ulangi Scan
            </button>
        </div>
    </div>

    <!-- html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrCode;
        const feedbackEl = document.getElementById('scan-feedback');
        const feedbackIcon = document.getElementById('feedback-icon');
        const feedbackTitle = document.getElementById('feedback-title');
        const feedbackMsg = document.getElementById('feedback-msg');

        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanning after success
            html5QrCode.stop().then(() => {
                submitAttendance(decodedText);
            }).catch(err => console.error(err));
        }

        function submitAttendance(code) {
            // UI Feedback: Loading
            showFeedback('loading', 'Memproses...', 'Sedang mencatat kehadiranmu...');

            fetch("{{ route('siswa.absensi.scan.submit') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        qr_code: code
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showFeedback('success', 'Berhasil!', data.message);
                        // Redirect after a delay
                        setTimeout(() => window.location.href = "{{ route('siswa.absensi.index') }}", 2000);
                    } else {
                        showFeedback('error', 'Gagal', data.message);
                    }
                })
                .catch(err => {
                    showFeedback('error', 'Error', 'Terjadi gangguan koneksi.');
                    console.error(err);
                });
        }

        function showFeedback(type, title, msg) {
            feedbackEl.classList.remove('hidden');
            feedbackTitle.innerText = title;
            feedbackMsg.innerText = msg;

            if (type === 'loading') {
                feedbackIcon.className =
                    "w-12 h-12 rounded-full mx-auto flex items-center justify-center text-2xl bg-indigo-50 text-indigo-500";
                feedbackIcon.innerHTML = "<i class='bx bx-loader-alt animate-spin'></i>";
            } else if (type === 'success') {
                feedbackIcon.className =
                    "w-12 h-12 rounded-full mx-auto flex items-center justify-center text-2xl bg-green-50 text-green-500";
                feedbackIcon.innerHTML = "<i class='bx bx-check-circle'></i>";
            } else {
                feedbackIcon.className =
                    "w-12 h-12 rounded-full mx-auto flex items-center justify-center text-2xl bg-red-50 text-red-500";
                feedbackIcon.innerHTML = "<i class='bx bx-error-alt'></i>";
            }
        }

        function startScanner() {
            html5QrCode = new Html5Qrcode("reader");
            const config = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            };

            html5QrCode.start({
                    facingMode: "environment"
                }, config, onScanSuccess)
                .catch(err => {
                    showFeedback('error', 'Kamera Error', 'Izin kamera diperlukan untuk melakukan scan.');
                });
        }

        function restartScanner() {
            feedbackEl.classList.add('hidden');
            if (html5QrCode) {
                html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: 250
                }, onScanSuccess);
            } else {
                startScanner();
            }
        }

        document.addEventListener('DOMContentLoaded', startScanner);
    </script>
@endsection
