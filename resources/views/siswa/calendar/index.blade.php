@extends('layouts.siswa_mobile')

@section('title', 'Kalender Belajar')

@section('content')
    <div class="space-y-6 pb-24">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-gray-900 leading-tight">Kalender</h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Jadwal & Deadline</p>
            </div>
            <div
                class="w-12 h-12 bg-white rounded-2xl border border-gray-100 flex items-center justify-center text-indigo-600 shadow-sm">
                <i class='bx bx-calendar text-2xl'></i>
            </div>
        </div>

        <!-- Calendar Card (Simple Month View) -->
        <div class="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm space-y-4">
            <div class="flex justify-between items-center mb-2">
                <h3 class="font-bold text-gray-800">{{ now()->isoFormat('MMMM YYYY') }}</h3>
                <div class="flex gap-2">
                    <button class="p-2 bg-gray-50 rounded-xl text-gray-400 hover:text-indigo-600 transition-colors"><i
                            class='bx bx-chevron-left'></i></button>
                    <button class="p-2 bg-gray-50 rounded-xl text-gray-400 hover:text-indigo-600 transition-colors"><i
                            class='bx bx-chevron-right'></i></button>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-1 text-center">
                @php $daysInWeek = ['S', 'S', 'R', 'K', 'J', 'S', 'M']; @endphp
                @foreach ($daysInWeek as $d)
                    <div class="text-[10px] font-black text-gray-300 uppercase py-2">{{ $d }}</div>
                @endforeach

                @php
                    $startOfMonth = now()->startOfMonth();
                    $endOfMonth = now()->endOfMonth();
                    $currentDay = $startOfMonth->copy()->startOfWeek(Carbon\Carbon::MONDAY);
                    $endDay = $endOfMonth->copy()->endOfWeek(Carbon\Carbon::SUNDAY);
                @endphp

                @while ($currentDay <= $endDay)
                    @php
                        $isToday = $currentDay->isToday();
                        $isCurrentMonth = $currentDay->isCurrentMonth();
                        $dateStr = $currentDay->toDateString();
                        $hasEvents = collect($events)->where('date', $dateStr);
                    @endphp
                    <div class="relative py-3">
                        <span
                            class="text-xs font-bold {{ $isToday ? 'bg-indigo-600 text-white w-8 h-8 flex items-center justify-center rounded-xl mx-auto shadow-lg shadow-indigo-100' : ($isCurrentMonth ? 'text-gray-700' : 'text-gray-200') }}">
                            {{ $currentDay->day }}
                        </span>
                        @if ($hasEvents->count() > 0)
                            <div class="flex justify-center gap-0.5 mt-1">
                                @foreach ($hasEvents->take(3) as $ev)
                                    <div
                                        class="w-1 h-1 rounded-full {{ $ev['color'] == 'orange' ? 'bg-orange-400' : 'bg-blue-400' }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @php $currentDay->addDay(); @endphp
                @endwhile
            </div>
        </div>

        <!-- Deadlines & Schedule Section -->
        <div class="space-y-4">
            <h3 class="font-black text-gray-900 text-sm px-1 uppercase tracking-widest">Mendatang</h3>

            <div class="space-y-3">
                @forelse(collect($events)->sortBy('date') as $ev)
                    @php
                        $isExpired = \Carbon\Carbon::parse($ev['date'])->isPast();
                        $daysRemaining = now()->diffInDays(\Carbon\Carbon::parse($ev['date']), false);
                    @endphp
                    <div
                        class="bg-white rounded-3xl p-4 border border-gray-100 shadow-sm flex items-center gap-4 transition-all active:scale-95 leading-tight">
                        <div
                            class="w-12 h-12 rounded-2xl {{ $ev['color'] == 'orange' ? 'bg-orange-50 text-orange-500' : 'bg-blue-50 text-blue-500' }} flex flex-col items-center justify-center shrink-0">
                            <span class="text-sm font-black">{{ \Carbon\Carbon::parse($ev['date'])->format('d') }}</span>
                            <span
                                class="text-[8px] font-black uppercase">{{ \Carbon\Carbon::parse($ev['date'])->format('M') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-gray-900 truncate">{{ $ev['title'] }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="text-[10px] font-bold {{ $daysRemaining < 0 ? 'text-red-400' : ($daysRemaining < 3 ? 'text-orange-400' : 'text-green-400') }}">
                                    {{ $daysRemaining < 0 ? 'Berakhir' : ($daysRemaining == 0 ? 'Hari Ini' : $daysRemaining . ' Hari Lagi') }}
                                </span>
                                <span class="w-1 h-1 bg-gray-200 rounded-full"></span>
                                <span
                                    class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $ev['type'] }}</span>
                            </div>
                        </div>
                        <i class='bx bx-chevron-right text-gray-300 text-xl'></i>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-400 text-xs italic">Tidak ada agenda mendatang.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
