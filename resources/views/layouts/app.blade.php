<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr"
    data-assets-path="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/') }}/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ $appSettings->nama_sekolah }}</title>

    <!-- Favicon -->
    @php
        $favicon = $appSettings->favicon
            ? Storage::url($appSettings->favicon)
            : asset('sneat-1.0.0/sneat-1.0.0/assets/img/favicon/favicon.ico');
        // Simple cache busting
        $favicon .= '?v=' . time();
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/css/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet"
        href="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    @stack('styles')

    <!-- Helpers -->
    <script src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo" style="height: auto; min-height: 64px; padding: 1rem;">
                    <a href="#" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            @if ($appSettings->logo_sekolah)
                                <img src="{{ Storage::url($appSettings->logo_sekolah) }}" alt="Logo Sekolah"
                                    style="max-width: 50px; max-height: 50px;">
                            @else
                                <i class='bx bxs-graduation' style="font-size: 32px; color: #696cff;"></i>
                            @endif
                        </span>
                        <span class="app-brand-text demo menu-text fw-bolder ms-2"
                            style="white-space: normal !important; line-height: 1.2; font-size: 0.9rem; text-transform: uppercase; text-align: left; display: inline-block; width: 12rem; word-wrap: break-word;">
                            {{ $appSettings->nama_sekolah }}
                        </span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    @if (auth()->user()->peran === 'admin')
                        <!-- Dashboard -->
                        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div>Dashboard</div>
                            </a>
                        </li>

                        <!-- Manajemen User -->
                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Manajemen
                                User</span>
                        </li>

                        @can('kelola pengguna')
                            <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active open' : '' }}">
                                <a href="javascript:void(0);" class="menu-link menu-toggle">
                                    <i class="menu-icon tf-icons bx bx-user"></i>
                                    <div>Kelola Pengguna</div>
                                </a>
                                <ul class="menu-sub">
                                    <li
                                        class="menu-item {{ request()->routeIs('admin.users.index') && !request()->has('role') ? 'active' : '' }}">
                                        <a href="{{ route('admin.users.index') }}" class="menu-link">
                                            <div>Semua Pengguna</div>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ request()->get('role') == 'guru' ? 'active' : '' }}">
                                        <a href="{{ route('admin.users.index', ['role' => 'guru']) }}" class="menu-link">
                                            <div>Guru</div>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ request()->get('role') == 'siswa' ? 'active' : '' }}">
                                        <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="menu-link">
                                            <div>Siswa</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        <!-- Akademik -->
                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Akademik</span>
                        </li>

                        @can('kelola tahun ajaran')
                            <li
                                class="menu-item {{ request()->routeIs('admin.pengaturan-akademik.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.pengaturan-akademik.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-cog"></i>
                                    <div>Tahun Akademik</div>
                                </a>
                            </li>
                        @endcan

                        @can('kelola kelas')
                            <li class="menu-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.kelas.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-buildings"></i>
                                    <div>Data Kelas</div>
                                </a>
                            </li>
                        @endcan

                        @can('kelola mapel')
                            <li class="menu-item {{ request()->routeIs('admin.mata-pelajaran.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.mata-pelajaran.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-book"></i>
                                    <div>Mata Pelajaran</div>
                                </a>
                            </li>
                        @endcan

                        @can('kelola jadwal')
                            <li class="menu-item {{ request()->routeIs('admin.jadwal-pelajaran.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.jadwal-pelajaran.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                                    <div>Jadwal Pelajaran</div>
                                </a>
                            </li>
                        @endcan

                        <!-- Pembelajaran & Evaluasi -->
                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Pembelajaran &
                                Evaluasi</span></li>

                        @can('kelola materi')
                            <li class="menu-item {{ request()->routeIs('admin.materi.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.materi.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-file"></i>
                                    <div>Data Materi</div>
                                </a>
                            </li>
                        @endcan

                        @can('kelola tugas')
                            <li class="menu-item {{ request()->routeIs('admin.tugas.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.tugas.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-task"></i>
                                    <div>Data Tugas</div>
                                </a>
                            </li>
                        @endcan

                        @can('kelola kuis')
                            <li class="menu-item {{ request()->routeIs('admin.kuis.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.kuis.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-edit"></i>
                                    <div>Data Kuis</div>
                                </a>
                            </li>
                        @endcan

                        @can('kelola ujian')
                            <li class="menu-item {{ request()->routeIs('admin.ujian.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.ujian.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-notepad"></i>
                                    <div>Data Ujian</div>
                                </a>
                            </li>
                        @endcan

                        <!-- Monitoring -->
                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Laporan</span>
                        </li>

                        <li class="menu-item {{ request()->routeIs('admin.absensi.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.absensi.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-check-circle"></i>
                                <div>Log Absensi</div>
                            </a>
                        </li>

                        <li class="menu-item {{ request()->routeIs('admin.nilai.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.nilai.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                                <div>Rekap Nilai</div>
                            </a>
                        </li>

                        <li class="menu-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.laporan.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-file-find"></i>
                                <div>Laporan Akademik</div>
                            </a>
                        </li>

                        <!-- System -->
                        <!-- System -->
                        <li class="menu-header small text-uppercase"><span class="menu-header-text">System</span></li>

                        <li class="menu-item {{ request()->routeIs('admin.audit-log.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.audit-log.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-history"></i>
                                <div>Audit Log</div>
                            </a>
                        </li>

                        <li
                            class="menu-item {{ request()->routeIs('admin.pengaturan-sekolah.index') ? 'active' : '' }}">
                            <a href="{{ route('admin.pengaturan-sekolah.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-cog"></i>
                                <div>Identitas Sekolah</div>
                            </a>
                        </li>
                    @elseif(auth()->user()->peran === 'guru')
                        <!-- Dashboard -->
                        <li class="menu-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('guru.dashboard') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div>Dashboard</div>
                            </a>
                        </li>

                        <!-- Pembelajaran -->
                        <li class="menu-header small text-uppercase"><span
                                class="menu-header-text">Pembelajaran</span>
                        </li>

                        @can('kelola materi')
                            <li
                                class="menu-item {{ request()->routeIs('guru.materi.*') || request()->routeIs('guru.pendahuluan.*') ? 'active' : '' }}">
                                <a href="{{ route('guru.pendahuluan.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-book-content"></i>
                                    <div>Materi & Pendahuluan</div>
                                </a>
                            </li>
                        @endcan

                        @can('lihat jadwal')
                            <li class="menu-item {{ request()->routeIs('guru.jadwal.index') ? 'active' : '' }}">
                                <a href="{{ route('guru.jadwal.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-calendar"></i>
                                    <div>Jadwal Mengajar</div>
                                </a>
                            </li>
                        @endcan

                        @can('kelola tugas')
                            <li class="menu-item {{ request()->routeIs('guru.tugas.*') ? 'active' : '' }}">
                                <a href="{{ route('guru.tugas.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-task"></i>
                                    <div>Tugas Siswa</div>
                                </a>
                            </li>
                        @endcan

                        @can('kelola absensi')
                            <li
                                class="menu-item {{ request()->routeIs('guru.absensi.verifikasi.index') ? 'active' : '' }}">
                                <a href="{{ route('guru.absensi.verifikasi.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-user-check"></i>
                                    <div>Verifikasi Absensi</div>
                                </a>
                            </li>
                        @endcan

                        <!-- Evaluasi -->
                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Evaluasi</span>
                        </li>

                        @can('kelola kuis')
                            <li class="menu-item {{ request()->routeIs('guru.kuis.*') ? 'active' : '' }}">
                                <a href="{{ route('guru.kuis.index') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-edit"></i>
                                    <div>Kuis</div>
                                </a>
                            </li>
                        @endcan

                        @can('kelola ujian')
                            <li class="menu-item {{ request()->routeIs('guru.ujian.*') ? 'active open' : '' }}">
                                <a href="javascript:void(0);" class="menu-link menu-toggle">
                                    <i class="menu-icon tf-icons bx bx-notepad"></i>
                                    <div>Ujian</div>
                                </a>
                                <ul class="menu-sub">
                                    <li class="menu-item {{ request()->routeIs('guru.ujian.index') ? 'active' : '' }}">
                                        <a href="{{ route('guru.ujian.index') }}" class="menu-link">
                                            <div>Daftar Ujian</div>
                                        </a>
                                    </li>
                                    <li class="menu-item {{ request()->routeIs('guru.ujian.create') ? 'active' : '' }}">
                                        <a href="{{ route('guru.ujian.create') }}" class="menu-link">
                                            <div>Buat Ujian Baru</div>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endcan

                        <!-- Laporan -->
                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Laporan</span>
                        </li>

                        @can('kelola absensi')
                            <li class="menu-item {{ request()->routeIs('guru.laporan.absensi') ? 'active' : '' }}">
                                <a href="{{ route('guru.laporan.absensi') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-check-circle"></i>
                                    <div>Rekap Absensi</div>
                                </a>
                            </li>
                        @endcan

                        @can('lihat rekap nilai')
                            <li class="menu-item {{ request()->routeIs('guru.laporan.nilai') ? 'active' : '' }}">
                                <a href="{{ route('guru.laporan.nilai') }}" class="menu-link">
                                    <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                                    <div>Rekap Nilai</div>
                                </a>
                            </li>
                        @endcan
                    @elseif(auth()->user()->peran === 'siswa')
                        <!-- Dashboard -->
                        <li class="menu-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('siswa.dashboard') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div>Dashboard</div>
                            </a>
                        </li>

                        <!-- Pembelajaran -->
                        <li class="menu-header small text-uppercase"><span
                                class="menu-header-text">Pembelajaran</span>
                        </li>

                        <li class="menu-item {{ request()->routeIs('siswa.pembelajaran.*') ? 'active' : '' }}">
                            <a href="{{ route('siswa.pembelajaran.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-book-open"></i>
                                <div>Jadwal & Materi</div>
                            </a>
                        </li>

                        <li class="menu-item {{ request()->routeIs('siswa.tugas.*') ? 'active' : '' }}">
                            <a href="{{ route('siswa.tugas.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-task"></i>
                                <div>Tugas Siswa</div>
                            </a>
                        </li>

                        <!-- Evaluasi -->
                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Evaluasi</span>
                        </li>

                        <li class="menu-item {{ request()->routeIs('siswa.kuis.*') ? 'active' : '' }}">
                            <a href="{{ route('siswa.kuis.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-pencil"></i>
                                <div>Kuis</div>
                            </a>
                        </li>

                        <li class="menu-item {{ request()->routeIs('siswa.ujian.*') ? 'active' : '' }}">
                            <a href="{{ route('siswa.ujian.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-notepad"></i>
                                <div>Ujian</div>
                            </a>
                        </li>

                        <!-- Laporan -->
                        <li class="menu-header small text-uppercase"><span class="menu-header-text">Laporan</span>
                        </li>

                        <li class="menu-item {{ request()->routeIs('siswa.nilai.*') ? 'active' : '' }}">
                            <a href="{{ route('siswa.nilai.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                                <div>Rekap Nilai</div>
                            </a>
                        </li>

                        <li class="menu-item {{ request()->routeIs('siswa.absensi.*') ? 'active' : '' }}">
                            <a href="{{ route('siswa.absensi.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-check-circle"></i>
                                <div>Rekap Absensi</div>
                            </a>
                        </li>
                    @endif
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
                                    <span class="badge bg-label-primary ms-2">
                                        {{ $activeAkademik->tahun_ajaran }} -
                                        {{ ucfirst($activeAkademik->semester) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Theme Toggle Removed -->

                            <!-- Notifications -->
                            @include('partials.notifications')
                            <!--/ Notifications -->

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->user()->foto_profil ? Storage::url(auth()->user()->foto_profil) : asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}"
                                            alt class="w-px-40 h-auto rounded-circle"
                                            style="object-fit: cover; aspect-ratio: 1/1;" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ auth()->user()->foto_profil ? Storage::url(auth()->user()->foto_profil) : asset('sneat-1.0.0/sneat-1.0.0/assets/img/avatars/1.png') }}"
                                                            alt class="w-px-40 h-auto rounded-circle"
                                                            style="object-fit: cover; aspect-ratio: 1/1;" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span
                                                        class="fw-semibold d-block">{{ auth()->user()->nama_lengkap }}</span>
                                                    <small
                                                        class="text-muted">{{ ucfirst(auth()->user()->peran) }}</small>
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
                                    @if (auth()->user()->peran === 'admin')
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('admin.pengaturan-sekolah.index') }}">
                                                <i class="bx bx-cog me-2"></i>
                                                <span class="align-middle">Pengaturan Sekolah</span>
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal"
                                            data-bs-target="#logoutModal">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Logout</span>
                                        </a>
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
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , made with ❤️ by
                                <a href="https://themeselection.com" target="_blank"
                                    class="footer-link fw-bolder">ThemeSelection</a>
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
    <script src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}">
    </script>
    <script src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/vendor/js/menu.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('sneat-1.0.0/sneat-1.0.0/assets/js/main.js') }}"></script>

    @stack('scripts')

    <!-- Force Bootstrap Dropdown Reset -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });
        });
    </script>

    <!-- Dynamic Delete Modal -->
    @include('partials.delete-modal-dynamic')

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar dari aplikasi?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Ya, Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @stack('modals')
</body>

</html>
