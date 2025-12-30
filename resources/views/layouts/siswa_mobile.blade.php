<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'E-Learning')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/siswa.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="h-full pb-24">
    <!-- Top Bar -->
    <header
        class="bg-white/80 backdrop-blur-md sticky top-0 z-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl overflow-hidden border border-gray-200">
                <img src="{{ Auth::user()->profile_photo_url ? Storage::url(Auth::user()->profile_photo_url) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama_lengkap) . '&color=7F9CF5&background=EBF4FF' }}"
                    class="w-full h-full object-cover">
            </div>
            <div>
                <h1 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Halo,</h1>
                <p class="text-sm font-bold text-gray-800 leading-tight">
                    {{ explode(' ', auth()->user()->nama_lengkap)[0] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('siswa.notifications.index') }}"
                class="relative w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-500 active:bg-gray-100 transition-colors">
                <i class='bx bx-bell text-xl'></i>
                <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
            </a>
        </div>
    </header>

    <!-- Content -->
    <main class="p-4 space-y-6">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 pb-safe">
        <div class="flex justify-around items-center h-16">
            <a href="{{ route('siswa.dashboard') }}"
                class="flex flex-col items-center justify-center w-full h-full text-xs font-medium {{ request()->routeIs('siswa.dashboard') ? 'text-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
                <i class='bx {{ request()->routeIs('siswa.dashboard') ? 'bxs-home' : 'bx-home' }} text-2xl mb-1'></i>
                <span>Beranda</span>
            </a>

            <a href="{{ route('siswa.pembelajaran.index') }}"
                class="flex flex-col items-center justify-center w-full h-full text-xs font-medium {{ request()->routeIs('siswa.pembelajaran.*') ? 'text-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
                <i
                    class='bx {{ request()->routeIs('siswa.pembelajaran.*') ? 'bxs-book-open' : 'bx-book-open' }} text-2xl mb-1'></i>
                <span>Belajar</span>
            </a>

            <div class="relative -top-6">
                <a href="{{ route('siswa.absensi.scan') }}"
                    class="flex flex-col items-center justify-center w-14 h-14 rounded-full bg-indigo-600 text-white shadow-lg shadow-indigo-200 ring-4 ring-white active:scale-90 transition-transform">
                    <i class='bx bx-qr-scan text-2xl'></i>
                </a>
            </div>

            <a href="{{ route('siswa.tugas.index') }}"
                class="flex flex-col items-center justify-center w-full h-full text-xs font-medium {{ request()->routeIs('siswa.tugas.*') ? 'text-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
                <i
                    class='bx {{ request()->routeIs('siswa.tugas.*') ? 'bxs-edit-alt' : 'bx-edit-alt' }} text-2xl mb-1'></i>
                <span>Tugas</span>
            </a>

            <a href="{{ route('profile.index') }}"
                class="flex flex-col items-center justify-center w-full h-full text-xs font-medium {{ request()->routeIs('profile.*') ? 'text-indigo-600' : 'text-gray-400 hover:text-gray-600' }}">
                <i class='bx {{ request()->routeIs('profile.*') ? 'bxs-user' : 'bx-user' }} text-2xl mb-1'></i>
                <span>Akun</span>
            </a>
        </div>
        <!-- Home Indicator (Cosmetic) -->
        <div class="flex justify-center pb-1">
            <div class="w-24 h-1 bg-gray-100 rounded-full"></div>
        </div>
    </nav>

    <!-- Logout Modal -->
    <div id="logoutModal"
        class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-sm p-6 space-y-4 shadow-xl">
            <h3 class="text-lg font-bold text-gray-900">Keluar Aplikasi?</h3>
            <p class="text-gray-500">Anda harus login kembali untuk mengakses materi.</p>
            <div class="flex gap-3">
                <button onclick="document.getElementById('logoutModal').classList.add('hidden')"
                    class="flex-1 px-4 py-2 rounded-xl border border-gray-200 text-gray-600 font-medium">Batal</button>
                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 rounded-xl bg-red-500 text-white font-medium">Ya,
                        Keluar</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
