<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Siswa - {{ \App\Models\PengaturanAplikasi::getSettings()->nama_sekolah }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/siswa.css', 'resources/js/app.js'])

    {!! htmlScriptTagJsApi() !!}

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0px);
            }
        }
    </style>
</head>

<body class="h-full bg-slate-50 overflow-hidden">
    @php
        $settings = \App\Models\PengaturanAplikasi::getSettings();
        $logo = $settings->logo_sekolah ? \Storage::url($settings->logo_sekolah) : null;
    @endphp

    <div class="h-full flex flex-col relative">
        <!-- Decorative Background Elements -->
        <div class="absolute top-[-10%] right-[-10%] w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl animate-float">
        </div>
        <div class="absolute bottom-[-5%] left-[-5%] w-48 h-48 bg-purple-600/10 rounded-full blur-2xl"></div>

        <!-- Top Section: Brand & Welcome -->
        <div class="px-8 pt-16 pb-8 text-center relative z-10">
            <div
                class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-3xl shadow-xl shadow-indigo-100 mb-6 p-4">
                @if ($logo)
                    <img src="{{ $logo }}" alt="Logo" class="w-full h-full object-contain">
                @else
                    <i class='bx bxs-graduation text-4xl text-indigo-600'></i>
                @endif
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2 uppercase">
                Masuk <span class="text-indigo-600">Belajar</span>
            </h1>
            <p class="text-gray-500 font-medium text-sm">Ayo lanjutkan petualangan belajarmu!</p>
        </div>

        <!-- Middle Section: Login Form -->
        <div class="flex-1 px-8 relative z-10">
            <div
                class="bg-white/80 backdrop-blur-xl border border-white rounded-[40px] p-8 shadow-2xl shadow-indigo-100/50">
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div
                            class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-3 animate-shake">
                            <i class='bx bx-error-circle text-lg'></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="space-y-2">
                        <label for="email"
                            class="text-[10px] font-black text-indigo-600 uppercase tracking-widest ml-1">Alamat
                            Email</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                <i class='bx bx-envelope text-xl'></i>
                            </div>
                            <input type="email" name="email" id="email"
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl text-sm font-semibold text-gray-800 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 transition-all outline-none"
                                placeholder="nama@sekolah.com" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password"
                            class="text-[10px] font-black text-indigo-600 uppercase tracking-widest ml-1">Kata
                            Sandi</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                <i class='bx bx-lock-alt text-xl'></i>
                            </div>
                            <input type="password" name="password" id="password"
                                class="w-full pl-12 pr-12 py-4 bg-gray-50 border-none rounded-2xl text-sm font-semibold text-gray-800 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 transition-all outline-none"
                                placeholder="••••••••" required>
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-indigo-600">
                                <i id="passwordIcon" class='bx bx-show text-xl'></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between px-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="remember" class="peer hidden">
                                <div
                                    class="w-5 h-5 border-2 border-gray-200 rounded-lg peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all">
                                </div>
                                <i
                                    class='bx bx-check absolute text-white opacity-0 peer-checked:opacity-100 left-0.5'></i>
                            </div>
                            <span
                                class="text-xs font-bold text-gray-500 group-hover:text-indigo-600 transition-colors">Ingat
                                Saya</span>
                        </label>
                    </div>

                    @if (config('recaptcha.api_site_key'))
                        <div class="flex items-center justify-center py-2">
                            {!! htmlFormSnippet() !!}
                        </div>
                        @error('g-recaptcha-response')
                            <p class="text-[10px] font-bold text-red-500 text-center mb-4">{{ $message }}</p>
                        @enderror
                    @endif

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-indigo-200 active:scale-[0.98] transition-all hover:bg-indigo-700">
                        Masuk Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Section: Footer/Help -->
        <div class="px-8 py-10 text-center relative z-10">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Butuh Bantuan?</p>
            <div class="flex gap-4 justify-center">
                <a href="#"
                    class="w-10 h-10 bg-white rounded-xl shadow-md flex items-center justify-center text-indigo-600">
                    <i class='bx bxl-whatsapp text-xl'></i>
                </a>
                <a href="#"
                    class="w-10 h-10 bg-white rounded-xl shadow-md flex items-center justify-center text-indigo-600">
                    <i class='bx bx-info-circle text-xl'></i>
                </a>
            </div>
            <p class="mt-8 text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                © {{ date('Y') }} {{ $settings->nama_sekolah }}
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('passwordIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            }
        }
    </script>
</body>

</html>
