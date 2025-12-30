@extends('layouts.siswa_mobile')

@section('title', 'Notifikasi')

@section('content')
    <div class="space-y-6 pb-20">
        <!-- Header -->
        <div class="flex justify-between items-center px-1">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Notifikasi</h2>
                <p class="text-xs text-gray-500 font-medium">Jangan lewatkan pengumuman penting!</p>
            </div>
            @if ($notifications->count() > 0)
                <form action="{{ route('siswa.notifications.read_all') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-3 py-2 rounded-xl active:scale-95 transition-transform">
                        BACA SEMUA
                    </button>
                </form>
            @endif
        </div>

        <div class="space-y-3">
            @forelse($notifications as $notif)
                <div
                    class="relative bg-white p-5 rounded-[32px] border border-gray-100 shadow-sm flex gap-4 transition-all {{ $notif->read_at ? 'opacity-60' : 'ring-1 ring-indigo-50' }}">
                    @if (!$notif->read_at)
                        <div class="absolute top-5 right-5 w-2 h-2 bg-indigo-600 rounded-full"></div>
                    @endif

                    <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0">
                        @php
                            $icon = 'bx-bell';
                            $color = 'text-gray-400';
                            $type = $notif->data['type'] ?? 'info';
                            if ($type == 'tugas') {
                                $icon = 'bx-task';
                                $color = 'text-blue-500';
                            } elseif ($type == 'kuis') {
                                $icon = 'bx-brain';
                                $color = 'text-purple-500';
                            } elseif ($type == 'ujian') {
                                $icon = 'bx-time';
                                $color = 'text-red-500';
                            } elseif ($type == 'diskusi') {
                                $icon = 'bx-chat';
                                $color = 'text-green-500';
                            }
                        @endphp
                        <i class='bx {{ $icon }} {{ $color }} text-2xl'></i>
                    </div>

                    <div class="flex-1 space-y-1">
                        <div class="flex justify-between items-center">
                            <span
                                class="text-[8px] font-black uppercase tracking-widest text-slate-400">{{ $type }}</span>
                            <span
                                class="text-[8px] font-medium text-slate-400">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 leading-tight">
                            {{ $notif->data['title'] ?? 'Pemberitahuan Baru' }}</h4>
                        <p class="text-[11px] text-gray-500 leading-relaxed line-clamp-2">
                            {{ $notif->data['message'] ?? ($notif->data['body'] ?? 'Cek detail informasi terbaru di aplikasi.') }}
                        </p>

                        @if (!$notif->read_at)
                            <div class="pt-2">
                                <form action="{{ route('notifications.read', $notif->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="text-[10px] font-bold text-indigo-600 flex items-center gap-1">
                                        Tandai telah dibaca <i class='bx bx-check'></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-100 px-10">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-4">
                        <i class='bx bx-bell-off text-3xl text-gray-200'></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Inbox Kosong</h4>
                    <p class="text-[10px] text-gray-400 mt-1">Belum ada notifikasi baru untuk saat ini. Tetap semangat
                        belajar!</p>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
