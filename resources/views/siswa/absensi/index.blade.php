@extends('layouts.siswa_mobile')

@section('title', 'Absensi Hari Ini')

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Absensi</h2>
                <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider">{{ $dayName }},
                    {{ \Carbon\Carbon::parse($today)->format('d F Y') }}</p>
            </div>
            <a href="{{ route('siswa.absensi.riwayat') }}"
                class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-500 shadow-sm">
                <i class='bx bx-history text-xl'></i>
            </a>
        </div>

        @if ($pertemuanHariIni->isEmpty())
            <div class="bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 py-16 px-10 text-center">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-4">
                    <i class='bx bx-calendar-x text-4xl text-gray-200'></i>
                </div>
                <h4 class="font-bold text-gray-800">Tidak Ada Jadwal</h4>
                <p class="text-gray-400 text-xs mt-1">Belum ada pertemuan yang dijadwalkan oleh guru untuk hari ini.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($pertemuanHariIni as $pertemuan)
                    <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 relative overflow-hidden">
                        <!-- Left Status Strip -->
                        @php
                            $stripColor = 'bg-gray-200';
                            if ($pertemuan->absensi_saya) {
                                $stripColor =
                                    $pertemuan->absensi_saya->status == 'hadir'
                                        ? 'bg-green-500'
                                        : ($pertemuan->absensi_saya->status == 'alpha'
                                            ? 'bg-red-500'
                                            : 'bg-blue-400');
                            } elseif ($pertemuan->status == 'mulai') {
                                $stripColor = 'bg-indigo-500';
                            }
                        @endphp
                        <div class="absolute top-0 left-0 w-1.5 h-full {{ $stripColor }}"></div>

                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <span
                                    class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ $pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</span>
                                <h3 class="font-bold text-gray-900 text-base leading-tight mt-0.5">
                                    {{ $pertemuan->judul_pertemuan }}</h3>
                            </div>
                            <div class="text-right">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase">Jam</span>
                                <span class="text-xs font-bold text-gray-700">
                                    {{ \Carbon\Carbon::parse($pertemuan->waktu_mulai ?? $pertemuan->jam_mulai)->format('H:i') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-4 py-2 border-y border-gray-50">
                            <i class='bx bx-user text-gray-400'></i>
                            <span
                                class="text-xs text-gray-500 font-medium">{{ $pertemuan->guruMengajar->guru->nama_lengkap }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            @if ($pertemuan->absensi_saya)
                                <div class="flex items-center gap-2">
                                    <div
                                        class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider
                                        @if ($pertemuan->absensi_saya->status == 'hadir') bg-green-50 text-green-600 @elseif($pertemuan->absensi_saya->status == 'alpha') bg-red-50 text-red-600 @else bg-blue-50 text-blue-600 @endif">
                                        {{ $pertemuan->absensi_saya->status }}
                                    </div>
                                    <span class="text-[9px] text-gray-400 font-medium">Jam
                                        {{ \Carbon\Carbon::parse($pertemuan->absensi_saya->waktu_absen)->format('H:i') }}</span>
                                </div>
                                <button disabled
                                    class="px-4 py-2 rounded-xl bg-gray-50 text-gray-400 text-[10px] font-bold uppercase">Selesai</button>
                            @elseif ($pertemuan->status == 'mulai')
                                <div class="flex items-center gap-1 text-[10px] text-indigo-600 font-bold">
                                    <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full animate-pulse"></div>
                                    Sesi Berlangsung
                                </div>
                                <button
                                    onclick="openAbsenModal({{ $pertemuan->id }}, '{{ $pertemuan->guruMengajar->mataPelajaran->nama_mapel }}')"
                                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-100 active:scale-95 transition-all flex items-center gap-2">
                                    <i class='bx bx-camera text-sm'></i>
                                    Ambil Foto Selfie
                                </button>
                            @else
                                <span class="text-[10px] text-gray-400 font-bold uppercase">Sesi Belum Aktif</span>
                                <button disabled
                                    class="px-4 py-2 rounded-xl bg-gray-50 text-gray-400 text-[10px] font-bold uppercase">Ditutup</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Attendance Modal (Bottom Sheet Style for Mobile) -->
    <div id="absenModal"
        class="hidden fixed inset-0 z-70 items-end justify-center bg-black/60 backdrop-blur-[2px] overflow-y-auto">
        <div class="bg-white w-full max-w-lg rounded-t-[40px] p-8 space-y-6 animate-in slide-in-from-bottom duration-300">
            <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mb-2"></div>

            <div class="text-center space-y-2">
                <h3 class="text-xl font-black text-gray-900 leading-tight">Presensi Mandiri</h3>
                <p id="modalSubjectName" class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest"></p>
            </div>

            <!-- Selfie Preview -->
            <div
                class="relative w-full aspect-4/3 bg-slate-900 rounded-3xl overflow-hidden border-4 border-white shadow-xl shadow-indigo-100 group">
                <video id="video" class="w-full h-full object-cover -scale-x-100" autoplay playsinline></video>
                <canvas id="canvas" class="hidden w-full h-full object-cover -scale-x-100"></canvas>
                <img id="selfie-preview" class="hidden w-full h-full object-cover -scale-x-100" src="">

                <div id="camera-overlay" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                    <div class="w-48 h-64 border-2 border-white/30 rounded-full"></div>
                </div>

                <div class="absolute bottom-4 left-0 right-0 flex justify-center px-4">
                    <button type="button" id="btn-capture" onclick="captureSelfie()"
                        class="px-6 py-3 bg-white/20 backdrop-blur-md border border-white/30 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all flex items-center gap-2">
                        <i class='bx bx-camera text-xl'></i>
                        Ambil Foto
                    </button>
                    <button type="button" id="btn-retake" onclick="resetCamera()"
                        class="hidden px-6 py-3 bg-white text-indigo-600 rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all items-center gap-2">
                        <i class='bx bx-refresh text-xl'></i>
                        Ulangi
                    </button>
                </div>
            </div>

            <form action="{{ route('siswa.absensi.store') }}" method="POST" class="space-y-6" id="form-absen">
                @csrf
                <input type="hidden" name="pertemuan_id" id="modalPertemuanId">
                <input type="hidden" name="foto_selfie" id="inputFotoSelfie">

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] pl-1">Status
                        Kehadiran</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="status" value="hadir" checked class="peer hidden"
                                onchange="toggleKeterangan(false)">
                            <div
                                class="py-4 border-2 border-gray-100 rounded-2xl flex flex-col items-center gap-2 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 transition-all">
                                <i class='bx bxs-check-circle text-2xl text-gray-300 peer-checked:text-indigo-600'></i>
                                <span class="text-[9px] font-black text-gray-500 peer-checked:text-indigo-600">HADIR</span>
                            </div>
                        </label>
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="status" value="izin" class="peer hidden"
                                onchange="toggleKeterangan(true)">
                            <div
                                class="py-4 border-2 border-gray-100 rounded-2xl flex flex-col items-center gap-2 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                <i class='bx bxs-info-circle text-2xl text-gray-300 peer-checked:text-blue-500'></i>
                                <span class="text-[9px] font-black text-gray-500 peer-checked:text-blue-500">IZIN</span>
                            </div>
                        </label>
                        <label class="relative group cursor-pointer">
                            <input type="radio" name="status" value="sakit" class="peer hidden"
                                onchange="toggleKeterangan(true)">
                            <div
                                class="py-4 border-2 border-gray-100 rounded-2xl flex flex-col items-center gap-2 peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all">
                                <i class='bx bxs-plus-medical text-2xl text-gray-300 peer-checked:text-orange-500'></i>
                                <span class="text-[9px] font-black text-gray-500 peer-checked:text-orange-500">SAKIT</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div id="ket-container" class="space-y-2 hidden animate-in slide-in-from-top-2 duration-300">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] pl-1">Alasan /
                        Keterangan</label>
                    <textarea name="keterangan" rows="2" placeholder="Tuliskan alasan izin/sakit..."
                        class="w-full bg-gray-50 rounded-2xl p-4 border border-gray-100 text-xs font-bold focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeAbsenModal()"
                        class="flex-1 py-4 text-gray-400 font-extrabold text-[10px] uppercase tracking-widest">Batal</button>
                    <button type="submit" id="btn-submit"
                        class="flex-2 bg-indigo-600 text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-indigo-100 active:scale-95 transition-all">
                        Kirim Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let stream = null;
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const preview = document.getElementById('selfie-preview');
        const inputStore = document.getElementById('inputFotoSelfie');

        function openAbsenModal(id, name) {
            document.getElementById('modalPertemuanId').value = id;
            document.getElementById('modalSubjectName').innerText = name;
            const modal = document.getElementById('absenModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            startCamera();
        }

        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "user"
                    },
                    audio: false
                });
                video.srcObject = stream;
            } catch (err) {
                console.error("Error accessing camera:", err);
                showToast("Izin kamera diperlukan untuk presensi mandiri.", "error");
            }
        }

        function captureSelfie() {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const dataUrl = canvas.toDataURL('image/png');
            inputStore.value = dataUrl;

            preview.src = dataUrl;
            preview.classList.remove('hidden');
            video.classList.add('hidden');
            document.getElementById('camera-overlay').classList.add('hidden');

            document.getElementById('btn-capture').classList.add('hidden');
            document.getElementById('btn-retake').classList.remove('hidden');
            document.getElementById('btn-retake').classList.add('flex');
        }

        function resetCamera() {
            preview.classList.add('hidden');
            video.classList.remove('hidden');
            document.getElementById('camera-overlay').classList.remove('hidden');
            document.getElementById('btn-capture').classList.remove('hidden');
            document.getElementById('btn-retake').classList.add('hidden');
            document.getElementById('btn-retake').classList.remove('flex');
            inputStore.value = '';
        }

        function closeAbsenModal() {
            const modal = document.getElementById('absenModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            resetCamera();
        }

        function toggleKeterangan(show) {
            const container = document.getElementById('ket-container');
            if (show) container.classList.remove('hidden');
            else container.classList.add('hidden');
        }

        document.getElementById('form-absen').addEventListener('submit', function(e) {
            if (!inputStore.value) {
                const status = document.querySelector('input[name="status"]:checked').value;
                if (status === 'hadir') {
                    e.preventDefault();
                    showToast("Silakan ambil foto selfie terlebih dahulu!", "warning");
                }
            }
        });

        document.getElementById('absenModal').addEventListener('click', function(e) {
            if (e.target === this) closeAbsenModal();
        });
    </script>
@endsection
