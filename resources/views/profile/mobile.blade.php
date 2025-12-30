@extends('layouts.siswa_mobile')

@section('title', 'Profil Saya')

@section('content')
    <div class="space-y-6 pb-12">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Profil Saya</h2>
            <button
                onclick="document.getElementById('logoutModal').classList.remove('hidden');document.getElementById('logoutModal').classList.add('flex');"
                class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center">
                <i class='bx bx-log-out text-xl'></i>
            </button>
        </div>

        @if (session('success'))
            <div
                class="bg-green-50 text-green-600 p-4 rounded-2xl border border-green-100 text-xs font-bold animate-in fade-in zoom-in duration-300">
                <i class='bx bx-check-circle me-1'></i> {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div
                class="bg-red-50 text-red-600 p-4 rounded-2xl border border-red-100 text-xs font-bold space-y-1 animate-in fade-in zoom-in duration-300">
                @foreach ($errors->all() as $error)
                    <p><i class='bx bx-error-circle me-1'></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Profile Card -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-full -mr-16 -mt-16 z-0"></div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="relative z-10 space-y-6 text-center">
                @csrf
                @method('PATCH')

                <div class="relative inline-block group">
                    <img id="uploadedAvatar"
                        src="{{ $user->foto_profil ? Storage::url($user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama_lengkap) . '&color=7F9CF5&background=EBF4FF' }}"
                        class="w-24 h-24 rounded-3xl object-cover border-4 border-white shadow-md mx-auto" alt="Avatar">
                    <label for="upload"
                        class="absolute bottom-0 right-[-10px] w-8 h-8 bg-indigo-600 text-white rounded-lg flex items-center justify-center shadow-lg cursor-pointer active:scale-90 transition-transform">
                        <i class='bx bx-camera'></i>
                        <input type="file" id="upload" name="foto_profil" hidden accept="image/*">
                    </label>
                </div>

                <div class="space-y-1">
                    <h3 class="text-xl font-bold text-gray-900">{{ $user->nama_lengkap }}</h3>
                    <p class="text-sm text-indigo-600 font-bold uppercase tracking-widest">{{ $user->peran }} • NIS
                        {{ $user->nis ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-1 gap-4 text-left">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">E-Mail</label>
                        <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100 text-gray-700 text-sm">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">No.
                            Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}"
                            class="w-full bg-white rounded-xl px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">Alamat</label>
                        <textarea name="alamat" rows="2"
                            class="w-full bg-white rounded-xl px-4 py-3 border border-gray-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all">{{ old('alamat', $user->alamat) }}</textarea>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-bold shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        <!-- Security Card -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-6">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                    <i class='bx bx-lock-alt'></i>
                </div>
                <h4 class="font-bold text-gray-900">Keamanan</h4>
            </div>

            <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Password Saat Ini</label>
                    <input type="password" name="current_password" required placeholder="••••••••"
                        class="w-full bg-gray-50 rounded-xl px-4 py-3 border border-gray-100 text-sm outline-none focus:bg-white focus:ring-2 focus:ring-orange-500 transition-all">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Password Baru</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                        class="w-full bg-gray-50 rounded-xl px-4 py-3 border border-gray-100 text-sm outline-none focus:bg-white focus:ring-2 focus:ring-orange-500 transition-all">
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ulangi Password
                        Baru</label>
                    <input type="password" name="password_confirmation" required placeholder="Konfirmasi password"
                        class="w-full bg-gray-50 rounded-xl px-4 py-3 border border-gray-100 text-sm outline-none focus:bg-white focus:ring-2 focus:ring-orange-500 transition-all">
                </div>

                <button type="submit"
                    class="w-full py-4 rounded-2xl font-bold bg-white text-gray-800 border-2 border-gray-100 active:scale-95 transition-all">
                    Update Password
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('upload').onchange = function(evt) {
            const [file] = this.files
            if (file) {
                document.getElementById('uploadedAvatar').src = URL.createObjectURL(file)
            }
        }
    </script>
@endsection
