@extends('layouts.siswa_mobile')

@section('title', 'Materi Pertemuan')

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Back Button & Title -->
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.pembelajaran.show', $pertemuan->guru_mengajar_id) }}"
                class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center text-gray-600 shadow-sm">
                <i class='bx bx-chevron-left text-2xl'></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900 leading-tight line-clamp-1">Pertemuan
                    {{ $pertemuan->pertemuan_ke }}</h2>
                <p class="text-xs text-gray-400 font-medium">{{ $pertemuan->judul_pertemuan }}</p>
            </div>
        </div>

        <!-- Attendance / Absensi Section -->
        @php
            $myAbsensi = \App\Models\Absensi::where('pertemuan_id', $pertemuan->id)
                ->where('siswa_id', auth()->id())
                ->first();
        @endphp

        <div class="bg-white rounded-3xl p-4 shadow-sm border border-gray-100">
            @if ($myAbsensi)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                            <i class='bx bx-check-double text-xl'></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-sm">Presensi Berhasil</h4>
                            <p class="text-[10px] text-gray-400">Status: {{ ucfirst($myAbsensi->status) }}</p>
                        </div>
                    </div>
                </div>
            @elseif($pertemuan->aktif)
                <form action="{{ route('siswa.pembelajaran.absen', $pertemuan->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-green-600 text-white py-4 rounded-2xl font-bold flex items-center justify-center gap-2 active:scale-95 transition-all shadow-lg shadow-green-100">
                        <i class='bx bx-user-check text-xl'></i>
                        Isi Daftar Hadir
                    </button>
                </form>
            @else
                <div class="text-center py-2">
                    <p class="text-xs text-gray-400 font-medium"><i class='bx bx-lock-alt me-1'></i> Presensi belum dibuka
                        atau sudah ditutup</p>
                </div>
            @endif
        </div>

        <!-- Materi List -->
        <div class="space-y-4">
            <div class="flex justify-between items-center px-1">
                <h3 class="font-bold text-gray-800 text-lg">Materi Pelajaran</h3>
                <span
                    class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg uppercase tracking-wider">{{ $pertemuan->materiPembelajaran->count() }}
                    File</span>
            </div>

            @forelse($pertemuan->materiPembelajaran as $materi)
                @php
                    $isLearned = \App\Models\ProgresMateri::where('user_id', auth()->id())
                        ->where('materi_id', $materi->id)
                        ->exists();
                    $isBookmarked = \App\Models\Bookmark::where('user_id', auth()->id())
                        ->where('bookmarkable_id', $materi->id)
                        ->where('bookmarkable_type', \App\Models\MateriPembelajaran::class)
                        ->exists();

                    $typeClass = match ($materi->tipe_materi) {
                        'video' => 'bg-red-100 text-red-600',
                        'file' => 'bg-orange-100 text-orange-600',
                        'link' => 'bg-blue-100 text-blue-600',
                        default => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 {{ $typeClass }}">
                        @if ($materi->tipe_materi == 'video')
                            <i class='bx bxl-youtube text-2xl'></i>
                        @elseif($materi->tipe_materi == 'file')
                            <i class='bx bxs-file-pdf text-2xl'></i>
                        @elseif($materi->tipe_materi == 'link')
                            <i class='bx bx-link text-2xl'></i>
                        @else
                            <i class='bx bx-text text-2xl'></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-2">
                            <h4 class="font-bold text-gray-900 text-sm leading-tight truncate">{{ $materi->judul_materi }}
                            </h4>
                            <button onclick="toggleBookmark({{ $materi->id }}, 'materi', this)"
                                class="shrink-0 {{ $isBookmarked ? 'text-orange-500' : 'text-gray-300' }} hover:text-orange-500 transition-colors">
                                <i class='bx {{ $isBookmarked ? 'bxs-bookmark' : 'bx-bookmark' }} text-lg'></i>
                            </button>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-1 line-clamp-2">{{ $materi->deskripsi }}</p>
                    </div>
                </div>

                <!-- Media Content -->
                <div class="rounded-2xl overflow-hidden bg-gray-50">
                    @if ($materi->tipe_materi == 'video')
                        <div class="aspect-video w-full">
                            <iframe class="w-full h-full"
                                src="{{ str_replace(['youtu.be/', 'watch?v='], ['www.youtube.com/embed/', 'embed/'], $materi->video_url) }}"
                                allowfullscreen></iframe>
                        </div>
                    @elseif($materi->tipe_materi == 'teks')
                        <div class="p-4 text-xs text-gray-600 leading-relaxed font-medium">
                            {!! nl2br(e($materi->konten)) !!}
                        </div>
                    @elseif($materi->tipe_materi == 'link')
                        <a href="{{ $materi->link_url }}" target="_blank"
                            class="flex items-center justify-between p-4 group">
                            <span class="text-xs font-bold text-blue-600 truncate mr-4">{{ $materi->link_url }}</span>
                            <i class='bx bx-link-external text-blue-400 group-hover:translate-x-1 transition-transform'></i>
                        </a>
                    @elseif($materi->tipe_materi == 'file')
                        <div class="p-4 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class='bx bxs-file text-gray-300'></i>
                                <span
                                    class="text-[10px] font-bold text-gray-500 uppercase">{{ round($materi->file_size / 1024) }}
                                    KB</span>
                            </div>
                            @if ($materi->dapat_diunduh)
                                <a href="{{ Storage::url($materi->file_path) }}" download
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-transform shadow-md shadow-indigo-100">Unduh</a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <div class="relative flex items-center">
                            <input type="checkbox" onclick="markAsLearned({{ $materi->id }}, this)"
                                {{ $isLearned ? 'checked disabled' : '' }}
                                class="peer appearance-none w-5 h-5 border-2 border-gray-200 rounded-lg checked:bg-green-500 checked:border-green-500 transition-all">
                            <i
                                class='bx bx-check absolute text-white scale-0 peer-checked:scale-100 transition-transform left-0.5'></i>
                        </div>
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest {{ $isLearned ? 'text-green-600' : 'text-gray-400 group-hover:text-indigo-600' }}">
                            {{ $isLearned ? 'Materi Selesai' : 'Tandai Selesai' }}
                        </span>
                    </label>
                </div>
        </div>
    @empty
        <div class="text-center py-8">
            <p class="text-gray-400 text-sm">Materi belum tersedia.</p>
        </div>
        @endforelse
    </div>

    <!-- Tab Bar for Additional Info -->
    <div class="space-y-4">
        <h3 class="font-bold text-gray-800 text-lg px-1">Aktivitas & Diskusi</h3>

        <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100">
            <!-- Discussion Feed -->
            <div class="p-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h4 class="font-bold text-gray-700 text-sm">Pertanyaan & Diskusi</h4>
                <span id="comment-count"
                    class="text-[10px] font-bold bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full">...</span>
            </div>

            <div id="diskusi-container" class="p-4 space-y-6 min-h-[100px] max-h-[400px] overflow-y-auto hide-scrollbar">
                <!-- Dynamic Discussion Content -->
                <div class="flex justify-center py-8" id="diskusi-loading">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                </div>
            </div>

            <!-- Input Field -->
            <div class="p-4 bg-white border-t border-gray-100">
                <div id="reply-info" class="hidden mb-2">
                    <div class="bg-indigo-50 px-3 py-1.5 rounded-lg flex justify-between items-center">
                        <span class="text-[10px] font-bold text-indigo-600">...</span>
                        <i class="bx bx-x text-sm text-indigo-400 cursor-pointer" onclick="cancelReply()"></i>
                    </div>
                </div>
                <form id="form-diskusi" class="flex items-end gap-2">
                    <input type="hidden" name="parent_id" id="diskusi-parent-id" value="">
                    <div
                        class="flex-1 bg-gray-50 rounded-2xl p-2 border border-gray-100 focus-within:border-indigo-300 transition-colors">
                        <textarea id="diskusi-pesan" name="pesan" rows="1" placeholder="Tanyakan sesuatu..."
                            class="w-full bg-transparent border-0 outline-none text-sm px-2 resize-none leading-relaxed min-h-[40px] max-h-[120px]"></textarea>
                    </div>
                    <button type="submit" id="btn-send-diskusi"
                        class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-100 active:scale-95 transition-all shrink-0">
                        <i class='bx bxs-send text-xl'></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Digital Notebook -->
        @php
            $myNote = \App\Models\CatatanPertemuan::where('user_id', auth()->id())
                ->where('pertemuan_id', $pertemuan->id)
                ->first();
        @endphp
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <i class='bx bx-edit-alt'></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-sm">Catatan Pribadi</h3>
                </div>
            </div>
            <textarea id="catatan-pribadi" rows="4"
                class="w-full bg-gray-50 rounded-2xl p-4 text-xs text-gray-600 border-0 outline-none focus:ring-2 focus:ring-orange-100 transition-all"
                placeholder="Tulis poin penting materi ini...">{{ $myNote?->konten }}</textarea>
            <button onclick="saveNote()" id="btn-save-note"
                class="w-full bg-orange-500 text-white py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-100 active:scale-95 transition-all">
                Simpan Catatan
            </button>
        </div>

        <!-- Activities Section (Tugas/Kuis) -->
        <div class="grid grid-cols-2 gap-4">
            @foreach ($pertemuan->tugas as $tgs)
                <a href="{{ route('siswa.tugas.show', $tgs->id) }}"
                    class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm space-y-2 group active:scale-95 transition-transform">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class='bx bx-task'></i>
                    </div>
                    <h5 class="font-bold text-gray-900 text-xs truncate">Tugas: {{ $tgs->judul_tugas }}</h5>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-blue-500">Buka Tugas ></p>
                </a>
            @endforeach

            @foreach ($pertemuan->kuis as $qz)
                <a href="{{ route('siswa.kuis.show', $qz->id) }}"
                    class="bg-white p-4 rounded-3xl border border-gray-100 shadow-sm space-y-2 group active:scale-95 transition-transform">
                    <div class="w-8 h-8 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                        <i class='bx bx-dice-5'></i>
                    </div>
                    <h5 class="font-bold text-gray-900 text-xs truncate">Kuis: {{ $qz->judul_kuis }}</h5>
                    <p class="text-[9px] font-bold uppercase tracking-wider text-red-500">Mulai Kuis ></p>
                </a>
            @endforeach
        </div>
    </div>
    </div>

    <!-- Discussion JS (Adapted from original) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('diskusi-container');
            const form = document.getElementById('form-diskusi');
            const input = document.getElementById('diskusi-pesan');
            const parentIdInput = document.getElementById('diskusi-parent-id');
            const replyInfo = document.getElementById('reply-info');
            const btnSend = document.getElementById('btn-send-diskusi');
            const counter = document.getElementById('comment-count');

            loadDiscussion();
            setInterval(loadDiscussion, 15000);

            function loadDiscussion() {
                fetch("{{ route('diskusi.index', $pertemuan->id) }}")
                    .then(r => r.json())
                    .then(data => {
                        counter.innerText = data.data.length;
                        if (data.data.length > 0) renderComments(data.data);
                        else renderEmpty();
                    });
            }

            function renderComments(comments) {
                let html = '';
                comments.forEach(c => {
                    const isMe = c.user_id == {{ auth()->id() }};
                    const isGuru = c.user.peran === 'guru';
                    html += `
                        <div class="animate-in fade-in slide-in-from-bottom-2 duration-300">
                            <div class="flex gap-3 ${isMe ? 'flex-row-reverse' : ''}">
                                <div class="w-8 h-8 rounded-xl shrink-0 flex items-center justify-center text-[10px] font-black
                                    ${isGuru ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-500'}">
                                    ${c.user.nama_lengkap.charAt(0)}
                                </div>
                                <div class="flex-1 space-y-1 ${isMe ? 'items-end' : ''} flex flex-col min-w-0">
                                    <div class="flex items-center gap-1.5 px-1 truncate max-w-full">
                                        <span class="text-[9px] font-bold text-gray-900 truncate">${c.user.nama_lengkap}</span>
                                        ${isGuru ? `<span class="bg-indigo-600 text-[6px] text-white px-1.5 py-0.5 rounded-full font-black uppercase tracking-widest shrink-0">Guru</span>` : ''}
                                    </div>
                                    <div class="p-3 rounded-2xl text-xs leading-relaxed max-w-[90%]
                                        ${isMe ? 'bg-indigo-600 text-white rounded-tr-none shadow-sm shadow-indigo-100' : 'bg-white border border-gray-100 text-gray-700 rounded-tl-none shadow-sm'}">
                                        ${c.pesan}
                                    </div>
                                    <div class="flex items-center gap-3 px-1">
                                        <span class="text-[8px] text-gray-400 font-bold">${formatDate(c.created_at)}</span>
                                        <button class="text-[8px] font-black text-indigo-500 uppercase tracking-widest" onclick="prepareReply(${c.id}, '${c.user.nama_lengkap}')">Balas</button>
                                        ${isMe ? `<button class="text-[8px] font-black text-red-400 uppercase tracking-widest" onclick="deleteComment(${c.id})">Hapus</button>` : ''}
                                    </div>

                                    <!-- Replies -->
                                    <div class="w-full space-y-3 mt-3">
                                        ${renderReplies(c.replies || [], isMe)}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }

            function renderReplies(replies, parentIsMe) {
                let html = '';
                replies.forEach(r => {
                    const isMe = r.user_id == {{ auth()->id() }};
                    html += `
                        <div class="flex gap-2 ${isMe ? 'flex-row-reverse' : ''} animate-in fade-in zoom-in duration-300">
                            <div class="w-6 h-6 rounded-lg shrink-0 bg-gray-50 flex items-center justify-center text-[8px] font-black text-gray-400">
                                ${r.user.nama_lengkap.charAt(0)}
                            </div>
                            <div class="max-w-[90%] space-y-1 ${isMe ? 'items-end' : ''} flex flex-col">
                                <div class="p-2 rounded-xl text-[10px] leading-relaxed
                                    ${isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-gray-100 text-gray-700 rounded-tl-none'}">
                                    ${r.pesan}
                                </div>
                                <div class="flex items-center gap-2 px-1">
                                    <span class="text-[7px] text-gray-400 font-bold">${formatDate(r.created_at)}</span>
                                    ${isMe ? `<button class="text-[7px] font-black text-red-300 uppercase" onclick="deleteComment(${r.id})">Hapus</button>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
                return html;
            }

            function renderEmpty() {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class='bx bx-message-square-dots text-4xl text-gray-200'></i>
                        <p class="text-xs text-gray-400 mt-2 font-medium">Belum ada diskusi. Jadilah yang pertama!</p>
                    </div>
                `;
            }

            function formatDate(str) {
                const d = new Date(str);
                return d.toLocaleString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    day: '2-digit',
                    month: 'short'
                });
            }

            window.prepareReply = (id, name) => {
                parentIdInput.value = id;
                replyInfo.classList.remove('hidden');
                replyInfo.querySelector('span').innerText = `Membalas @${name}`;
                input.focus();
            };

            window.cancelReply = () => {
                parentIdInput.value = '';
                replyInfo.classList.add('hidden');
            };

            window.deleteComment = (id) => {
                if (confirm('Hapus pesan ini?')) {
                    fetch(`/diskusi/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(() => loadDiscussion());
                }
            };

            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const msg = input.value.trim();
                if (!msg) return;

                btnSend.disabled = true;
                fetch("{{ route('diskusi.store', $pertemuan->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            pesan: msg,
                            parent_id: parentIdInput.value
                        })
                    })
                    .then(r => r.json())
                    .then(res => {
                        input.value = '';
                        cancelReply();
                        loadDiscussion();
                        container.scrollTop = container.scrollHeight;
                    })
                    .finally(() => btnSend.disabled = false);
            });

            window.markAsLearned = (materiId, checkbox) => {
                if (!checkbox.checked) return;

                fetch(`/siswa/materi/${materiId}/learned`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        checkbox.disabled = true;
                        checkbox.parentElement.nextElementSibling.innerText = 'Materi Selesai';
                        checkbox.parentElement.nextElementSibling.classList.replace('text-gray-400',
                            'text-green-600');
                    }
                });
            };

            window.toggleBookmark = (id, type, btn) => {
                fetch(`{{ route('pembelajaran.bookmark.toggle') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id,
                        type
                    })
                }).then(r => r.json()).then(res => {
                    const icon = btn.querySelector('i');
                    if (res.status === 'added') {
                        btn.classList.replace('text-gray-300', 'text-orange-500');
                        icon.classList.replace('bx-bookmark', 'bxs-bookmark');
                    } else {
                        btn.classList.replace('text-orange-500', 'text-gray-300');
                        icon.classList.replace('bxs-bookmark', 'bx-bookmark');
                    }
                });
            };

            window.saveNote = () => {
                const konten = document.getElementById('catatan-pribadi').value;
                const btn = document.getElementById('btn-save-note');

                btn.disabled = true;
                btn.innerText = 'Menyimpan...';

                fetch(`{{ route('pembelajaran.catatan', $pertemuan->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        konten
                    })
                }).then(r => r.json()).then(res => {
                    btn.innerText = 'Tersimpan!';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerText = 'Simpan Catatan';
                    }, 2000);
                });
            };
        });
    </script>
@endsection
