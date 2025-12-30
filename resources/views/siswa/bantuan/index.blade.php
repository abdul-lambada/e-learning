@extends('layouts.siswa_mobile')

@section('title', 'Pusat Bantuan')

@section('content')
    <div class="space-y-8 pb-20">
        <!-- Header -->
        <div class="text-center space-y-3 pt-4">
            <div
                class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-[32px] flex items-center justify-center mx-auto shadow-sm shadow-indigo-100">
                <i class='bx bx-support text-4xl'></i>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Pusat Bantuan</h2>
                <p class="text-xs text-gray-400 font-medium">Ada yang bisa kami bantu, Siswa?</p>
            </div>
        </div>

        <!-- Support Channels -->
        <div class="grid grid-cols-2 gap-4">
            <a href="https://wa.me/6285156553226" target="_blank"
                class="bg-white p-5 rounded-[32px] border border-gray-100 shadow-sm flex flex-col items-center text-center gap-2 active:scale-95 transition-all">
                <div class="w-10 h-10 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center">
                    <i class='bx bxl-whatsapp text-2xl'></i>
                </div>
                <span class="text-[10px] font-bold text-gray-700 uppercase tracking-tight">WhatsApp</span>
                <p class="text-[8px] text-gray-400 leading-tight">Respon cepat via Chat</p>
            </a>
            <a href="mailto:engineertekno@gmail.com"
                class="bg-white p-5 rounded-[32px] border border-gray-100 shadow-sm flex flex-col items-center text-center gap-2 active:scale-95 transition-all">
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center">
                    <i class='bx bx-envelope text-2xl'></i>
                </div>
                <span class="text-[10px] font-bold text-gray-700 uppercase tracking-tight">Email CS</span>
                <p class="text-[8px] text-gray-400 leading-tight">Ketik keluhanmu</p>
            </a>
        </div>

        <!-- FAQ Sections -->
        <div class="space-y-4">
            <h3 class="font-bold text-gray-800 text-lg px-2">Pertanyaan Populer</h3>

            <div class="space-y-3">
                <!-- FAQ Item 1 -->
                <div
                    class="faq-item bg-white rounded-[24px] border border-gray-100 overflow-hidden shadow-sm transition-all">
                    <button onclick="toggleFaq(this)" class="w-full p-5 flex justify-between items-center text-left gap-4">
                        <span class="text-xs font-bold text-gray-800">Lupa password akun?</span>
                        <i class='bx bx-chevron-down text-gray-400 transition-transform duration-300'></i>
                    </button>
                    <div class="faq-content hidden px-5 pb-5">
                        <p class="text-[11px] text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-2xl">
                            Silakan hubungi Wali Kelas atau Admin IT sekolah untuk melakukan permohonan reset kata sandi.
                            Pastikan membawa kartu identitas siswa.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div
                    class="faq-item bg-white rounded-[24px] border border-gray-100 overflow-hidden shadow-sm transition-all">
                    <button onclick="toggleFaq(this)" class="w-full p-5 flex justify-between items-center text-left gap-4">
                        <span class="text-xs font-bold text-gray-800">Tidak bisa klik tombol absen?</span>
                        <i class='bx bx-chevron-down text-gray-400 transition-transform duration-300'></i>
                    </button>
                    <div class="faq-content hidden px-5 pb-5">
                        <p class="text-[11px] text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-2xl">
                            Tombol absen hanya aktif jika waktu pertemuan sudah dimulai dan Guru pengampu telah mengaktifkan
                            sesi presensi di sistem.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div
                    class="faq-item bg-white rounded-[24px] border border-gray-100 overflow-hidden shadow-sm transition-all">
                    <button onclick="toggleFaq(this)" class="w-full p-5 flex justify-between items-center text-left gap-4">
                        <span class="text-xs font-bold text-gray-800">Format & Ukuran Tugas?</span>
                        <i class='bx bx-chevron-down text-gray-400 transition-transform duration-300'></i>
                    </button>
                    <div class="faq-content hidden px-5 pb-5">
                        <p class="text-[11px] text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-2xl">
                            Kamu bisa mengirim file format PDF, DOCX, ZIP, atau Gambar (JPG/PNG). Pastikan ukuran file tidak
                            melebihi <span class="font-bold text-indigo-600">10 MB</span>.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documentation Card -->
        <div class="bg-indigo-600 rounded-[40px] p-8 text-white relative overflow-hidden shadow-lg shadow-indigo-100">
            <i class='bx bx-book-bookmark text-white/10 text-9xl absolute -right-4 -bottom-4'></i>
            <h4 class="text-lg font-bold mb-2">Panduan Lengkap</h4>
            <p class="text-xs text-indigo-100 mb-6 font-medium leading-relaxed">Pelajari seluruh fitur sistem e-learning
                mulai dari materi hingga ujian dalam satu buku panduan digital.</p>
            <button
                class="bg-white text-indigo-600 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">
                Download PDF
            </button>
        </div>
    </div>

    <script>
        function toggleFaq(btn) {
            const item = btn.closest('.faq-item');
            const content = item.querySelector('.faq-content');
            const icon = btn.querySelector('i');

            // Toggle Content
            const isHidden = content.classList.contains('hidden');

            // Actually, we could close others but let's keep it simple
            if (isHidden) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
                item.classList.add('ring-2', 'ring-indigo-100', 'border-indigo-200');
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
                item.classList.remove('ring-2', 'ring-indigo-100', 'border-indigo-200');
            }
        }
    </script>
@endsection
