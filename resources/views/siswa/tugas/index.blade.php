@extends('layouts.siswa_mobile')

@section('title', 'Tugas Saya')

@section('content')
    <div class="space-y-6 pb-8">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tugas Saya</h2>
                <p class="text-xs text-gray-500">Kelola dan kumpulkan tugas sekolahmu</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-600">
                <i class='bx bx-task text-2xl'></i>
            </div>
        </div>

        <!-- Filter/Stats Quick Bar -->
        <div class="flex gap-2 overflow-x-auto pb-2 hide-scrollbar">
            <a href="{{ route('siswa.tugas.index') }}"
                class="px-4 py-2 rounded-full whitespace-nowrap text-xs font-bold {{ !request()->has('status') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-white text-gray-500 border border-gray-100' }}">Semua</a>
            <a href="{{ route('siswa.tugas.index', ['status' => 'aktif']) }}"
                class="px-4 py-2 rounded-full whitespace-nowrap text-xs font-bold {{ request()->get('status') == 'aktif' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-100' : 'bg-white text-gray-500 border border-gray-100' }}">Belum
                Selesai</a>
        </div>

        <!-- Tugas List -->
        <div class="space-y-4">
            @forelse($tugasList as $t)
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 space-y-4 relative overflow-hidden">
                    <!-- Status Indicator Strip -->
                    @php
                        $statusColor = 'bg-yellow-400';
                        if ($t->pengumpulan) {
                            $statusColor = $t->pengumpulan->status == 'dinilai' ? 'bg-green-500' : 'bg-blue-500';
                        } elseif (now()->greaterThan($t->tanggal_deadline)) {
                            $statusColor = 'bg-red-500';
                        }
                    @endphp
                    <div class="absolute top-0 left-0 w-1.5 h-full {{ $statusColor }}"></div>

                    <div class="flex justify-between items-start">
                        <div class="flex-1 pr-4">
                            <span
                                class="text-[10px] uppercase tracking-wider font-bold text-indigo-500">{{ $t->pertemuan->guruMengajar->mataPelajaran->nama_mapel }}</span>
                            <h3 class="font-bold text-gray-900 leading-tight mt-1 line-clamp-2">{{ $t->judul }}</h3>
                        </div>

                        @if ($t->pengumpulan && $t->pengumpulan->nilai !== null)
                            <div class="bg-indigo-50 rounded-xl p-2 text-center min-w-[50px]">
                                <span class="block text-[8px] text-indigo-400 font-bold uppercase">Nilai</span>
                                <span
                                    class="text-lg font-black text-indigo-600 leading-none">{{ number_format($t->pengumpulan->nilai, 0) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-calendar text-gray-400 text-sm'></i>
                            <span
                                class="text-xs {{ now()->greaterThan($t->tanggal_deadline) ? 'text-red-500 font-bold' : 'text-gray-500' }}">
                                Deadline: {{ $t->tanggal_deadline->format('d M, H:i') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if ($t->pengumpulan)
                                <div
                                    class="flex items-center gap-1.5 text-[10px] font-bold py-1 px-2 rounded-lg {{ $t->pengumpulan->status == 'dinilai' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600' }}">
                                    <i class='bx bx-check-double'></i>
                                    {{ $t->pengumpulan->status == 'dinilai' ? 'Dinilai' : 'Dikumpulkan' }}
                                </div>
                            @else
                                <div
                                    class="flex items-center gap-1.5 text-[10px] font-bold py-1 px-2 rounded-lg {{ now()->greaterThan($t->tanggal_deadline) ? 'bg-red-50 text-red-600' : 'bg-yellow-50 text-yellow-600' }}">
                                    <i
                                        class='bx {{ now()->greaterThan($t->tanggal_deadline) ? 'bx-time-five' : 'bx-loader-circle' }}'></i>
                                    {{ now()->greaterThan($t->tanggal_deadline) ? 'Melewati Batas' : 'Belum Selesai' }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('siswa.tugas.show', $t->id) }}"
                        class="w-full py-3 rounded-xl font-bold text-center text-sm block active:scale-95 transition-all {{ $t->pengumpulan ? 'bg-gray-50 text-gray-600 border border-gray-100' : 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' }}">
                        {{ $t->pengumpulan ? 'Lihat Detail' : 'Kumpulkan Sekarang' }}
                    </a>
                </div>
            @empty
                <div class="bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 py-12 px-6 text-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mx-auto mb-4">
                        <i class='bx bx-party text-4xl text-indigo-300'></i>
                    </div>
                    <h4 class="font-bold text-gray-800">Semua Beres!</h4>
                    <p class="text-gray-400 text-xs mt-1">Tidak ada tugas yang perlu dikerjakan saat ini.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="py-4">
            {{ $tugasList->links() }}
        </div>
    </div>
@endsection
