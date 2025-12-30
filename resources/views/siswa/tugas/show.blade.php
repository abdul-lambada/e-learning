@extends('layouts.siswa_mobile')

@section('title', 'Detail Tugas')

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Back Button & Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.tugas.index') }}"
                class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-600 shadow-sm">
                <i class='bx bx-chevron-left text-2xl'></i>
            </a>
            <div class="min-w-0">
                <h2 class="text-xl font-bold text-gray-900 leading-tight truncate">Detail Tugas</h2>
                <p class="text-[10px] text-indigo-600 font-bold uppercase tracking-widest leading-none">Manajemen Penugasan
                </p>
            </div>
        </div>

        <!-- Task Info Card -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 space-y-4 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50/50 rounded-full -mr-16 -mt-16 z-0"></div>

            <div class="relative z-10 space-y-4">
                <div class="flex flex-col gap-1">
                    <span
                        class="text-[10px] font-bold text-indigo-500 uppercase tracking-widest">{{ $tugas->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</span>
                    <h1 class="text-xl font-black text-gray-900 leading-tight">{{ $tugas->judul }}</h1>
                </div>

                <div class="grid grid-cols-2 gap-3 py-4 border-y border-gray-50">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                            <i class='bx bx-time-five'></i>
                        </div>
                        <div>
                            <span class="block text-[8px] font-bold text-gray-400 uppercase">Deadline</span>
                            <span
                                class="text-[10px] font-bold text-gray-700">{{ $tugas->tanggal_deadline->format('d M, H:i') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-green-50 text-green-500 flex items-center justify-center">
                            <i class='bx bx-star'></i>
                        </div>
                        <div>
                            <span class="block text-[8px] font-bold text-gray-400 uppercase">Max Nilai</span>
                            <span class="text-[10px] font-bold text-gray-700">{{ $tugas->nilai_maksimal ?? 100 }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 pt-1">
                    <div class="prose prose-sm text-gray-600 leading-relaxed text-xs">
                        {!! nl2br(e($tugas->deskripsi)) !!}
                    </div>

                    @if ($tugas->instruksi)
                        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100">
                            <h4 class="text-[10px] font-bold text-blue-700 uppercase tracking-widest mb-1">Petunjuk Khusus
                            </h4>
                            <p class="text-[11px] text-blue-800 leading-relaxed font-medium">
                                {!! nl2br(e($tugas->instruksi)) !!}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grade & Feedback Section (If Evaluated) -->
        @if ($pengumpulan && $pengumpulan->status == 'dinilai')
            <div class="bg-indigo-600 rounded-[32px] p-6 text-white shadow-lg shadow-indigo-100 space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-indigo-100">Hasil Penilaian</h3>
                    <div
                        class="px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest text-white">
                        Sudah Dinilai</div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-4xl font-black">{{ $pengumpulan->nilai }}<span
                            class="text-sm font-medium text-indigo-300">/{{ $tugas->nilai_maksimal }}</span></div>
                    @if ($pengumpulan->komentar_guru)
                        <div class="flex-1 bg-white/10 p-3 rounded-2xl border border-white/10 italic text-[10px]">
                            "{{ $pengumpulan->komentar_guru }}"
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Submission Status & Form -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 space-y-6">
            <h3 class="font-bold text-gray-900 border-b border-gray-50 pb-3">Status Pengumpulan</h3>

            @if ($pengumpulan)
                <div class="space-y-4">
                    @if ($pengumpulan->filePengumpulan->isNotEmpty())
                        <div class="space-y-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">File Terkirim</span>
                            @foreach ($pengumpulan->filePengumpulan as $file)
                                <div
                                    class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100 group">
                                    <div
                                        class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-400 shadow-sm">
                                        <i
                                            class='bx bxs-file-{{ in_array(pathinfo($file->nama_file, PATHINFO_EXTENSION), ['jpg', 'png', 'jpeg']) ? 'image' : 'pdf' }} text-xl'></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-gray-800 truncate">{{ $file->nama_file }}</p>
                                        <p class="text-[9px] text-gray-400 uppercase">
                                            {{ pathinfo($file->nama_file, PATHINFO_EXTENSION) }} •
                                            {{ round(Storage::size($file->path_file) / 1024, 1) }} KB</p>
                                    </div>
                                    <a href="{{ Storage::url($file->path_file) }}" target="_blank"
                                        class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-600 shadow-sm active:scale-90 transition-transform">
                                        <i class='bx bx-download text-xl'></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if ($pengumpulan->link_url)
                        <div class="space-y-2">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Link
                                Terlampir</span>
                            <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-2xl border border-blue-100">
                                <i class='bx bx-link text-blue-500 text-xl'></i>
                                <a href="{{ $pengumpulan->link_url }}" target="_blank"
                                    class="text-xs font-bold text-blue-600 truncate flex-1 underline decoration-blue-200">
                                    {{ $pengumpulan->link_url }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($pengumpulan->status != 'dinilai')
                        <div
                            class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl flex items-center gap-3 border border-emerald-100">
                            <i class='bx bx-check-double text-xl'></i>
                            <div class="flex-1">
                                <p class="text-[10px] font-black uppercase tracking-widest">Berhasil Dikirim</p>
                                <p class="text-[9px] font-medium leading-tight">Kamu masih bisa memperbarui tugas ini
                                    sebelum dinilai.</p>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-6 bg-gray-50 rounded-[32px] border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-3">
                        <i class='bx bx-cloud-upload text-3xl text-gray-300'></i>
                    </div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Belum Mengumpulkan</p>
                </div>
            @endif

            <!-- Form Section -->
            @if (!$pengumpulan || $pengumpulan->status != 'dinilai')
                <form action="{{ route('siswa.tugas.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-5 pt-4">
                    @csrf
                    <input type="hidden" name="tugas_id" value="{{ $tugas->id }}">

                    @if ($tugas->upload_file)
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-2">Pilih File
                                (Max 10MB)</label>
                            <div class="relative group">
                                <input type="file" name="files[]" multiple id="file_input" class="hidden"
                                    onchange="updateFileLabel(this)">
                                <label for="file_input"
                                    class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border border-gray-200 border-dashed cursor-pointer group-hover:border-indigo-400 transition-colors">
                                    <div
                                        class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-gray-400 shadow-sm">
                                        <i class='bx bx-plus text-2xl'></i>
                                    </div>
                                    <div>
                                        <p id="file_name" class="text-xs font-bold text-gray-700">Pilih berkas tugas...</p>
                                        <p class="text-[9px] text-gray-400 font-medium">Bisa PDF, DOC, atau Gambar</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endif

                    @if ($tugas->upload_link)
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-2">Tautan Tautan
                                (Google Drive/Lainnya)</label>
                            <input type="url" name="link_url" placeholder="https://..."
                                value="{{ $pengumpulan->link_url ?? '' }}"
                                class="w-full bg-gray-50 rounded-2xl px-5 py-4 border border-gray-100 text-xs font-bold text-gray-800 placeholder:text-gray-300 focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                    @endif

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold flex items-center justify-center gap-2 shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                        <i class='bx bx-paper-plane text-xl'></i>
                        {{ $pengumpulan ? 'Update Pengumpulan' : 'Kirim Tugas Sekarang' }}
                    </button>
                    @if (now()->greaterThan($tugas->tanggal_deadline))
                        <p class="text-center text-[9px] text-red-500 font-bold uppercase tracking-widest">Tugas Terlambat!
                            Tetap kumpulkan segera.</p>
                    @endif
                </form>
            @endif
        </div>
    </div>

    <script>
        function updateFileLabel(input) {
            const label = document.getElementById('file_name');
            if (input.files.length > 1) {
                label.innerText = input.files.length + ' file dipilih';
            } else if (input.files.length === 1) {
                label.innerText = input.files[0].name;
            } else {
                label.innerText = 'Pilih berkas tugas...';
            }
        }
    </script>
@endsection
