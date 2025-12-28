<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="/sneat-1.0.0/sneat-1.0.0/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Siswa E-Learning</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/sneat-1.0.0/sneat-1.0.0/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="/sneat-1.0.0/sneat-1.0.0/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="/sneat-1.0.0/sneat-1.0.0/assets/vendor/css/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="/sneat-1.0.0/sneat-1.0.0/assets/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="/sneat-1.0.0/sneat-1.0.0/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    @stack('styles')

    <!-- Helpers -->
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/js/helpers.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('siswa.dashboard') }}" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <i class='bx bxs-graduation' style="font-size: 32px; color: #696cff;"></i>
                        </span>
                        <span
                            class="app-brand-text demo menu-text fw-bolder ms-2">{{ $appSettings->nama_sekolah }}</span>
                    </a>
                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('siswa.dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div>Dashboard</div>
                        </a>
                    </li>

                    <!-- Kelas Saya -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Kelas Saya</span></li>

                    @can('lihat kelas')
                        <li class="menu-item">
                            <a href="#" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-buildings"></i>
                                <div>Informasi Kelas</div>
                            </a>
                        </li>
                    @endcan

                    @can('lihat mata pelajaran')
                        <li class="menu-item {{ request()->routeIs('siswa.pembelajaran.*') ? 'active' : '' }}">
                            <a href="{{ route('siswa.pembelajaran.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-book"></i>
                                <div>Mata Pelajaran</div>
                            </a>
                        </li>
                    @endcan

                    <li class="menu-item {{ request()->routeIs('siswa.pembelajaran.*') ? 'active' : '' }}">
                        <a href="{{ route('siswa.pembelajaran.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-collection"></i>
                            <div>Jadwal Pelajaran</div>
                        </a>
                    </li>



                    <!-- Tugas & Evaluasi -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Tugas & Evaluasi</span>
                    </li>

                    @can('lihat tugas')
                        <li class="menu-item {{ request()->routeIs('siswa.tugas.*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-task"></i>
                                <div>Tugas</div>
                            </a>
                            <ul class="menu-sub">
                                <li
                                    class="menu-item {{ request()->routeIs('siswa.tugas.index') && !request()->has('status') ? 'active' : '' }}">
                                    <a href="{{ route('siswa.tugas.index') }}" class="menu-link">
                                        <div>Daftar Tugas</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->get('status') == 'aktif' ? 'active' : '' }}">
                                    <a href="{{ route('siswa.tugas.index', ['status' => 'aktif']) }}" class="menu-link">
                                        <div>Tugas Aktif</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->get('status') == 'riwayat' ? 'active' : '' }}">
                                    <a href="{{ route('siswa.tugas.index', ['status' => 'riwayat']) }}" class="menu-link">
                                        <div>Riwayat Tugas</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('lihat kuis')
                        <li class="menu-item {{ request()->routeIs('siswa.kuis.*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-edit"></i>
                                <div>Kuis</div>
                            </a>
                            <ul class="menu-sub">
                                <li
                                    class="menu-item {{ request()->routeIs('siswa.kuis.index') && !request()->has('filter') ? 'active' : '' }}">
                                    <a href="{{ route('siswa.kuis.index') }}" class="menu-link">
                                        <div>Kuis Tersedia</div>
                                    </a>
                                </li>
                                <li class="menu-item {{ request()->get('filter') == 'riwayat' ? 'active' : '' }}">
                                    <a href="{{ route('siswa.kuis.index', ['filter' => 'riwayat']) }}" class="menu-link">
                                        <div>Hasil Kuis</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('lihat ujian')
                        <li class="menu-item {{ request()->routeIs('siswa.ujian.*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-notepad"></i>
                                <div>Ujian</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item {{ request()->routeIs('siswa.ujian.index') ? 'active' : '' }}">
                                    <a href="{{ route('siswa.ujian.index') }}" class="menu-link">
                                        <div>Jadwal Ujian</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="#" class="menu-link">
                                        <div>Hasil Ujian</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    <!-- Monitoring -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Monitoring</span></li>

                    @can('lihat absensi')
                        <li class="menu-item {{ request()->is('siswa/absensi*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-check-circle"></i>
                                <div>Absensi</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="#" class="menu-link">
                                        <div>Absensi Hari Ini</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="#" class="menu-link">
                                        <div>Riwayat Absensi</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('lihat nilai sendiri')
                        <li class="menu-item">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                                <div>Nilai</div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item {{ request()->routeIs('siswa.nilai.index') ? 'active' : '' }}">
                                    <a href="{{ route('siswa.nilai.index') }}" class="menu-link">
                                        <div>Nilai Saya</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a href="#" class="menu-link">
                                        <div>Rapor</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    <!-- Lainnya -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Lainnya</span></li>

                    <li class="menu-item {{ request()->routeIs('siswa.nilai.index') ? 'active' : '' }}">
                        <a href="{{ route('siswa.nilai.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                            <div>Nilai Saya</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->routeIs('forum.*') ? 'active' : '' }}">
                        <a href="{{ route('forum.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-chat"></i>
                            <div>Forum Diskusi</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->routeIs('perpustakaan.*') ? 'active' : '' }}">
                        <a href="{{ route('perpustakaan.index') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-library"></i>
                            <div>Perpustakaan</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="#" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-help-circle"></i>
                            <div>Bantuan</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <span class="fw-semibold">Selamat Datang, {{ auth()->user()->nama_lengkap }}</span>
                                @if ($activeAkademik)
                                    <span class="badge bg-label-warning ms-2">
                                        TA: {{ $activeAkademik->tahun_ajaran }}
                                        ({{ ucfirst($activeAkademik->semester) }})
                                    </span>
                                @endif
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Notifications -->
                            @include('partials.notifications')
                            <!--/ Notifications -->

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="/sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png"
                                                            alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span
                                                        class="fw-semibold d-block">{{ auth()->user()->nama_lengkap }}</span>
                                                    <small class="text-muted">Siswa</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.index') }}">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Profil Saya</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.index') }}">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Pengaturan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Logout</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Berhasil!</strong> {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Error!</strong> {{ session('error') }}
                            </div>
                        @endif

                        @if (session('warning'))
                            <div class="alert alert-warning alert-dismissible" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Perhatian!</strong> {{ session('warning') }}
                            </div>
                        @endif

                        @if (session('info'))
                            <div class="alert alert-info alert-dismissible" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Info!</strong> {{ session('info') }}
                            </div>
                        @endif

                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                © {{ date('Y') }}, E-Learning System - Portal Siswa
                            </div>
                            <div>
                                <span class="text-muted">NIS: {{ auth()->user()->nis }}</span>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/popper/popper.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/js/bootstrap.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/js/menu.js"></script>

    <!-- Main JS -->
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/js/main.js"></script>

    @stack('scripts')
</body>

</html>
