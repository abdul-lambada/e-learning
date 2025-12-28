@extends('layouts.siswa')

@section('title', 'Bantuan')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Siswa /</span> Bantuan</h4>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><i class="bx bx-book-open me-2"></i>Panduan Pembelajaran</h5>
                            <p class="card-text">
                                Temukan informasi bagaimana cara mengakses materi, mengerjakan tugas, dan mengikuti kuis.
                            </p>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item border-0 ps-0">
                                    <a href="{{ route('siswa.absensi.index') }}" class="text-body d-block">
                                        <i class="bx bx-chevron-right text-primary me-2"></i>Cara Absensi Harian
                                    </a>
                                </li>
                                <li class="list-group-item border-0 ps-0">
                                    <a href="{{ route('siswa.tugas.index') }}" class="text-body d-block">
                                        <i class="bx bx-chevron-right text-primary me-2"></i>Cara Unggah Tugas
                                    </a>
                                </li>
                                <li class="list-group-item border-0 ps-0">
                                    <a href="{{ route('siswa.kuis.index') }}" class="text-body d-block">
                                        <i class="bx bx-chevron-right text-primary me-2"></i>Mengerjakan Kuis Online
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title text-danger"><i class="bx bx-support me-2"></i>Kontak Admin Sekolah</h5>
                            <p class="card-text">
                                Jika Anda mengalami kendala teknis atau masalah akun, silakan hubungi admin sekolah melalui:
                            </p>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <i class="bx bx-phone me-2"></i> <strong>WhatsApp:</strong> 085156553226 (Admin Website
                                    Abdul Kholik)
                                </li>
                                <li class="mb-3">
                                    <i class="bx bx-envelope me-2"></i> <strong>Email:</strong> engineertekno@gmail.com
                                </li>
                                <li>
                                    <i class="bx bx-time me-2"></i> <strong>Jam Operasional:</strong> Senin - Jumat (08.00 -
                                    15.00)
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- FAQ Accordion -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Pertanyaan Umum (FAQ)</h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion mt-3" id="accordionExample">
                                <div class="accordion-item card">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#accordionOne" aria-expanded="false"
                                            aria-controls="accordionOne">
                                            Bagaimana jika saya lupa password?
                                        </button>
                                    </h2>
                                    <div id="accordionOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            Silakan hubungi Wali Kelas atau Admin Sekolah untuk melakukan reset password
                                            akun Anda.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item card">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#accordionTwo" aria-expanded="false"
                                            aria-controls="accordionTwo">
                                            Kenapa saya tidak bisa absen?
                                        </button>
                                    </h2>
                                    <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            Absensi hanya bisa dilakukan jika Guru pengampu mata pelajaran sudah memulai
                                            sesi pertemuan. Pastikan Anda mengakses menu Absensi pada jam pelajaran yang
                                            sesuai.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item card">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                            data-bs-target="#accordionThree" aria-expanded="false"
                                            aria-controls="accordionThree">
                                            Format file apa saja yang bisa diupload untuk tugas?
                                        </button>
                                    </h2>
                                    <div id="accordionThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            Sistem menerima format umum seperti PDF, DOCX, JPG, PNG, dan ZIP. Maksimal
                                            ukuran file adalah 10MB per file.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
