<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\GuruMengajar;
use App\Models\Pertemuan;
use App\Models\MateriPembelajaran;
use App\Models\Tugas;
use App\Models\Kuis;
use App\Models\JadwalUjian;
use App\Models\Ujian;
use App\Models\KelasSiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to truncate tables
        Schema::disableForeignKeyConstraints();

        // Truncate all relevant tables
        DB::table('kelas_siswa')->truncate();
        DB::table('absensi')->truncate();
        DB::table('jawaban_kuis')->truncate();
        DB::table('soal_kuis')->truncate();
        DB::table('kuis')->truncate();
        DB::table('pengumpulan_tugas')->truncate();
        DB::table('tugas')->truncate();
        DB::table('materi_pembelajaran')->truncate();
        DB::table('pertemuan')->truncate();
        DB::table('guru_mengajar')->truncate();
        DB::table('mata_pelajaran')->truncate();
        DB::table('kelas')->truncate();
        DB::table('users')->where('username', '!=', 'admin')->delete();

        Schema::enableForeignKeyConstraints();

        $this->command->info('Setting up Indonesian dummy data...');

        // 1. Mata Pelajaran
        $subjects = [
            ['kode' => 'MAT-01', 'nama' => 'Matematika Umum', 'deskripsi' => 'Belajar logika angka dan perhitungan.'],
            ['kode' => 'BIN-01', 'nama' => 'Bahasa Indonesia', 'deskripsi' => 'Pengembangan kemampuan berbahasa dan sastra.'],
            ['kode' => 'ING-01', 'nama' => 'Bahasa Inggris', 'deskripsi' => 'Communication in global language.'],
            ['kode' => 'RPL-01', 'nama' => 'Pemrograman Web', 'deskripsi' => 'Membangun aplikasi web modern.'],
            ['kode' => 'IPA-01', 'nama' => 'Fisika Dasar', 'deskripsi' => 'Hukum alam dan mekanika.'],
            ['kode' => 'PJK-01', 'nama' => 'Penjasorkes', 'deskripsi' => 'Kesehatan jasmani dan olahraga.'],
        ];

        $mapelModels = [];
        foreach ($subjects as $s) {
            $mapelModels[] = MataPelajaran::create([
                'kode_mapel' => $s['kode'],
                'nama_mapel' => $s['nama'],
                'deskripsi' => $s['deskripsi'],
                'aktif' => true,
            ]);
        }

        // 2. Guru Users
        $teachersData = [
            ['nama' => 'Dr. Bambang Subianto', 'email' => 'bambang@guru.com', 'user' => 'bambang', 'nip' => '197001012000011001'],
            ['nama' => 'Ibu Ratna Sari, M.Pd', 'email' => 'ratna@guru.com', 'user' => 'ratna', 'nip' => '198005052005012001'],
            ['nama' => 'Pak Andi Hermawan, S.Kom', 'email' => 'andi@guru.com', 'user' => 'andi', 'nip' => '198509092010011002'],
            ['nama' => 'Ibu Siti Aminah, S.Pd', 'email' => 'siti@guru.com', 'user' => 'siti', 'nip' => '199012122015012003'],
            ['nama' => 'Pak Joko Susilo, M.T', 'email' => 'joko@guru.com', 'user' => 'joko', 'nip' => '197503032003011004'],
        ];

        $teacherModels = [];
        foreach ($teachersData as $t) {
            $user = User::create([
                'nama_lengkap' => $t['nama'],
                'email' => $t['email'],
                'username' => $t['user'],
                'password' => Hash::make('password'),
                'peran' => 'guru',
                'nip' => $t['nip'],
                'jenis_kelamin' => str_contains($t['nama'], 'Ibu') ? 'P' : 'L',
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $user->assignRole('guru');
            $teacherModels[] = $user;
        }

        // 3. Kelas (Creating 6 classes)
        $kelasData = [
            ['kode' => 'X-RPL-1', 'nama' => 'X RPL 1', 'tingkat' => '10', 'jurusan' => 'RPL', 'wali' => $teacherModels[0]->id],
            ['kode' => 'X-TKJ-1', 'nama' => 'X TKJ 1', 'tingkat' => '10', 'jurusan' => 'TKJ', 'wali' => $teacherModels[1]->id],
            ['kode' => 'XI-RPL-1', 'nama' => 'XI RPL 1', 'tingkat' => '11', 'jurusan' => 'RPL', 'wali' => $teacherModels[2]->id],
            ['kode' => 'XI-MM-1', 'nama' => 'XI MM 1', 'tingkat' => '11', 'jurusan' => 'Multimedia', 'wali' => $teacherModels[3]->id],
            ['kode' => 'XII-RPL-1', 'nama' => 'XII RPL 1', 'tingkat' => '12', 'jurusan' => 'RPL', 'wali' => $teacherModels[4]->id],
            ['kode' => 'XII-TKJ-1', 'nama' => 'XII TKJ 1', 'tingkat' => '12', 'jurusan' => 'TKJ', 'wali' => $teacherModels[0]->id],
        ];

        $kelasModels = [];
        foreach ($kelasData as $k) {
            $kelasModels[] = Kelas::create([
                'kode_kelas' => $k['kode'],
                'nama_kelas' => $k['nama'],
                'tingkat' => $k['tingkat'],
                'jurusan' => $k['jurusan'],
                'wali_kelas_id' => $k['wali'],
                'tahun_ajaran' => '2024/2025',
                'kapasitas' => 36,
                'aktif' => true,
            ]);
        }

        // 4. Siswa Users (30 students, 5 per class)
        $siswaNames = [
            'Aditya Pratama', 'Bagus Wijaya', 'Citra Lestari', 'Dian Sastro', 'Eka Putra',
            'Fajar Ramadhan', 'Gita Gutawa', 'Hendra Kurniawan', 'Indah Permata', 'Joko Widodo',
            'Kiki Amalia', 'Lulu Tobing', 'Maman Abdurrahman', 'Nina Karlina', 'Oki Setiana',
            'Putra Bangsa', 'Qori Sandioriva', 'Rina Nose', 'Sule Prikitiw', 'Tukul Arwana',
            'Ussy Sulistiawaty', 'Vicky Prasetyo', 'Wulan Guritno', 'Xena Warrior', 'Yuni Shara',
            'Zaskia Gotik', 'Baim Wong', 'Chelsea Olivia', 'Deddy Corbuzier', 'Ernest Prakasa'
        ];

        $siswaModels = [];
        foreach ($siswaNames as $index => $name) {
            $username = strtolower(str_replace(' ', '.', $name));
            $user = User::create([
                'nama_lengkap' => $name,
                'email' => "$username@siswa.com",
                'username' => $username,
                'password' => Hash::make('password'),
                'peran' => 'siswa',
                'nis' => '2024' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'jenis_kelamin' => $index % 2 == 0 ? 'L' : 'P',
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $user->assignRole('siswa');
            $siswaModels[] = $user;

            // Assign to class (6 classes, 5 students each)
            $classIndex = (int)($index / 5);
            KelasSiswa::create([
                'kelas_id' => $kelasModels[$classIndex]->id,
                'siswa_id' => $user->id,
                'tahun_ajaran' => '2024/2025',
                'semester' => 'ganjil',
            ]);
        }

        // 5. Guru Mengajar (Jadwal)
        // Assign each teacher to a few subjects in different classes
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jadwalModels = [];

        foreach ($kelasModels as $ki => $kelas) {
            foreach ($mapelModels as $mi => $mapel) {
                // Determine teacher based on subject index or class index
                $teacher = $teacherModels[($ki + $mi) % 5];

                $gm = GuruMengajar::create([
                    'guru_id' => $teacher->id,
                    'mata_pelajaran_id' => $mapel->id,
                    'kelas_id' => $kelas->id,
                    'tahun_ajaran' => '2024/2025',
                    'semester' => 'ganjil',
                    'hari' => $days[($ki + $mi) % 5],
                    'jam_mulai' => '07:30',
                    'jam_selesai' => '09:00',
                ]);
                $jadwalModels[] = $gm;

                // 6. Pertemuan (Create 2 meetings for each subject)
                for ($p = 1; $p <= 2; $p++) {
                    $pertemuan = Pertemuan::create([
                        'guru_mengajar_id' => $gm->id,
                        'pertemuan_ke' => $p,
                        'judul_pertemuan' => "Pertemuan $p: Pengenalan " . $mapel->nama_mapel,
                        'deskripsi' => "Pada pertemuan ini kita akan membahas dasar-dasar " . $mapel->nama_mapel,
                        'tanggal_pertemuan' => Carbon::now()->subDays(14 - ($p * 7)),
                        'jam_mulai' => '07:30',
                        'jam_selesai' => '09:00',
                        'status' => 'selesai',
                        'aktif' => true,
                    ]);

                    // 7. Materi
                    MateriPembelajaran::create([
                        'pertemuan_id' => $pertemuan->id,
                        'judul_materi' => "Modul Utama " . $mapel->nama_mapel,
                        'deskripsi' => "Silakan baca modul ini sebelum kelas dimulai.",
                        'konten' => "Konten materi pembelajaran untuk " . $mapel->nama_mapel,
                        'tipe_materi' => 'teks',
                        'aktif' => true,
                    ]);

                    // 8. Tugas (Only on meeting 2)
                    if ($p == 2) {
                        Tugas::create([
                            'pertemuan_id' => $pertemuan->id,
                            'judul_tugas' => "Tugas Mandiri 1: " . $mapel->nama_mapel,
                            'deskripsi' => "Kerjakan soal-soal latihan di bab 1.",
                            'instruksi' => "Upload file jawaban dalam format PDF.",
                            'tanggal_mulai' => Carbon::now()->subDays(2),
                            'tanggal_deadline' => Carbon::now()->addDays(5),
                            'aktif' => true,
                        ]);
                    }
                }
            }
        }

        $this->command->info('Indonesian dummy data seeded successfully!');
    }
}
