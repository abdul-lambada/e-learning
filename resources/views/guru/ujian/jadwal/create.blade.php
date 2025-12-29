@extends('layouts.guru')
@section('title', 'Jadwalkan Ujian')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <x-card :title="'Jadwalkan: ' . $ujian->nama_ujian">
                <form action="{{ route('guru.ujian.jadwal.store', $ujian->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 text-primary">
                            <x-input label="Tanggal Ujian" type="date" name="tanggal_ujian" required />
                        </div>
                        <div class="col-md-3 text-primary">
                            <x-input label="Jam Mulai" type="time" name="jam_mulai" required />
                        </div>
                        <div class="col-md-3 text-primary">
                            <x-input label="Jam Selesai" type="time" name="jam_selesai" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-input label="Ruangan" name="ruangan" placeholder="Contoh: Lab Komputer 1" required />
                        </div>
                        <div class="col-md-6">
                            <x-select label="Pengawas" name="pengawas_id">
                                <option value="{{ auth()->id() }}">Diri Sendiri ({{ auth()->user()->nama_lengkap }})
                                </option>
                                @foreach ($gurus as $guru)
                                    @if ($guru->id !== auth()->id())
                                        <option value="{{ $guru->id }}">{{ $guru->nama_lengkap }}</option>
                                    @endif
                                @endforeach
                            </x-select>
                        </div>
                    </div>

                    <x-textarea label="Catatan (Opsional)" name="catatan" rows="2" />

                    <div class="mt-4">
                        <x-button type="submit" icon="bx-save">Simpan Jadwal</x-button>
                        <a href="{{ route('guru.ujian.show', $ujian->id) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-card>
        </div>
    </div>
@endsection
