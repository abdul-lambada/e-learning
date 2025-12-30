@extends('layouts.app')

@section('title', 'Pengaturan Sekolah')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Identitas Sekolah</h4>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Informasi Sekolah</h5>
                    <form action="{{ route('admin.pengaturan-sekolah.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                                <img src="{{ $settings->logo_sekolah ? asset('storage/' . $settings->logo_sekolah) : asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}"
                                    alt="logo-sekolah" class="d-block rounded" height="100" width="100"
                                    id="logoPreview" />
                                <div class="button-wrapper">
                                    <label for="logo_sekolah" class="btn btn-primary me-2" tabindex="0">
                                        <span class="d-none d-sm-block">Upload Logo Baru</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="logo_sekolah" name="logo_sekolah" hidden
                                            accept="image/png, image/jpeg" />
                                    </label>
                                    <p class="text-muted mb-0">Format JPG atau PNG. Maksimal 2MB.</p>
                                </div>
                            </div>
                            <hr class="my-4" />
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama Sekolah / Instansi</label>
                                    <input class="form-control" type="text" name="nama_sekolah"
                                        value="{{ old('nama_sekolah', $settings->nama_sekolah) }}" required />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Email Kontak</label>
                                    <input class="form-control" type="email" name="email_kontak"
                                        value="{{ old('email_kontak', $settings->email_kontak) }}" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">No. Telepon</label>
                                    <input class="form-control" type="text" name="no_telepon"
                                        value="{{ old('no_telepon', $settings->no_telepon) }}" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Nama Kepala Sekolah</label>
                                    <input class="form-control" type="text" name="nama_kepala_sekolah"
                                        value="{{ old('nama_kepala_sekolah', $settings->nama_kepala_sekolah) }}" />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">NIP Kepala Sekolah</label>
                                    <input class="form-control" type="text" name="nip_kepala_sekolah"
                                        value="{{ old('nip_kepala_sekolah', $settings->nip_kepala_sekolah) }}" />
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label class="form-label">Alamat Sekolah</label>
                                    <textarea class="form-control" name="alamat_sekolah" rows="3">{{ old('alamat_sekolah', $settings->alamat_sekolah) }}</textarea>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('logo_sekolah').onchange = function(evt) {
            const [file] = this.files
            if (file) {
                document.getElementById('logoPreview').src = URL.createObjectURL(file)
            }
        }
    </script>
@endpush
