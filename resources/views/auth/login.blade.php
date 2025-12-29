<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="/sneat-1.0.0/sneat-1.0.0/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login - E-Learning System</title>
    <meta name="description" content="Sistem E-Learning untuk SMA/SMK/MA" />

    <!-- Favicon -->
    <!-- Favicon -->
    @php
        $settings = \App\Models\PengaturanAplikasi::getSettings();
        $favicon = $settings->favicon
            ? Storage::url($settings->favicon)
            : asset('sneat-1.0.0/sneat-1.0.0/assets/img/favicon/favicon.ico');
        $favicon .= '?v=' . time();
        $logo = $settings->logo_sekolah ? Storage::url($settings->logo_sekolah) : null;
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}" />

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

    <!-- Page CSS -->
    <link rel="stylesheet" href="/sneat-1.0.0/sneat-1.0.0/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/js/helpers.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Login Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4">
                            <a href="/" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    @if ($logo)
                                        <img src="{{ $logo }}" alt="Logo"
                                            style="height: 40px; width: auto;">
                                    @else
                                        <i class='bx bxs-graduation' style="font-size: 40px; color: #696cff;"></i>
                                    @endif
                                </span>
                                <span
                                    class="app-brand-text demo text-body fw-bolder fs-3">{{ $settings->nama_sekolah }}</span>
                            </a>
                        </div>
                        <!-- /Logo -->

                        <h4 class="mb-2">Selamat Datang! 👋</h4>
                        <p class="mb-4">Silakan login untuk mengakses sistem E-Learning</p>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                                <strong>Error!</strong> {{ $errors->first() }}
                            </div>
                        @endif

                        <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Masukkan email Anda"
                                    value="{{ old('email') }}" autofocus required />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="············" aria-describedby="password" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember" />
                                    <label class="form-check-label" for="remember"> Ingat Saya </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <small class="text-muted">
                                Default password: <strong>password</strong>
                            </small>
                        </p>
                    </div>
                </div>
                <!-- /Login Card -->
            </div>
        </div>
    </div>
    <!-- / Content -->

    <!-- Core JS -->
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/popper/popper.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/js/bootstrap.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/vendor/js/menu.js"></script>

    <!-- Main JS -->
    <script src="/sneat-1.0.0/sneat-1.0.0/assets/js/main.js"></script>
</body>

</html>
