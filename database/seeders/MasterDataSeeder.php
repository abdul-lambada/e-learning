<?php

namespace Database\Seeders;

use App\Models\GuruMengajar;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PengaturanAkademik;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Setup Tahun Ajaran Aktif
        $akademik = PengaturanAkademik::create([
            'tahun_ajaran' => '2024/2025',
            'semester' => 'ganjil',
            'is_aktif' => true, // Aktif
        ]);
        $this->command->info('✓ Tahun Ajaran 2024/2025 Ganjil Created & Activated');

        // 2. Setup Mata Pelajaran
        $mapels = [
            ['kode' => 'MTK', 'nama' => 'Matematika', 'deskripsi' => 'Pelajaran menghitung', 'durasi' => 90],
            ['kode' => 'BIN', 'nama' => 'Bahasa Indonesia', 'deskripsi' => 'Pelajaran bahasa nasional', 'durasi' => 90],
            ['kode' => 'BIG', 'nama' => 'Bahasa Inggris', 'deskripsi' => 'English Language', 'durasi' => 90],
            ['kode' => 'IPA', 'nama' => 'Ilmu Pengetahuan Alam', 'deskripsi' => 'Fisika, Biologi, Kimia', 'durasi' => 120],
            ['kode' => 'IPS', 'nama' => 'Ilmu Pengetahuan Sosial', 'deskripsi' => 'Sejarah, Geografi, Ekonomi', 'durasi' => 90],
        ];

        foreach ($mapels as $m) {
            MataPelajaran::create([
                'kode_mapel' => $m['kode'],
                'nama_mapel' => $m['nama'],
                'deskripsi' => $m['deskripsi'],
                'durasi_pertemuan' => $m['durasi'],
                'jumlah_pertemuan' => 16,
                'aktif' => true,
            ]);
        }
        $this->command->info('✓ 5 Mata Pelajaran Created');

        // 3. Setup Kelas & Wali Kelas
        $gurus = User::role('guru')->get();
        if ($gurus->count() < 3) {
            $this->command->warn('! Not enough Guru users for 3 Classes. Skipping Wali Kelas assignment for some.');
        }

        $kelasData = [
            ['kode' => 'X-1', 'nama' => 'X IPA 1', 'tingkat' => '10', 'wali' => $gurus[0]->id ?? null],
            ['kode' => 'XI-1', 'nama' => 'XI IPA 1', 'tingkat' => '11', 'wali' => $gurus[1]->id ?? null],
            ['kode' => 'XII-1', 'nama' => 'XII IPA 1', 'tingkat' => '12', 'wali' => $gurus[2]->id ?? null],
        ];

        foreach ($kelasData as $k) {
            Kelas::create([
                'kode_kelas' => $k['kode'],
                'nama_kelas' => $k['nama'],
                'tingkat' => $k['tingkat'],
                'wali_kelas_id' => $k['wali'],
                'tahun_ajaran' => $akademik->tahun_ajaran,
                'aktif' => true,
            ]);
        }
        $this->command->info('✓ 3 Kelas Created (X, XI, XII)');

        // 4. Enroll Siswa ke Kelas (Semua siswa dummy masuk ke X IPA 1 dulu biar gampang demo)
        $siswas = User::role('siswa')->get();
        $kelasX = Kelas::where('kode_kelas', 'X-1')->first();

        if ($kelasX && $siswas->count() > 0) {
            // Gunakan metode attach via relasi belongsToMany dengan data pivot
            // Karena tabel kelas_siswa punya kolom tahun_ajaran & semester yang required
            $pivots = [];
            foreach ($siswas as $siswa) {
                $pivots[$siswa->id] = [
                    'tahun_ajaran' => $akademik->tahun_ajaran,
                    'semester' => $akademik->semester
                ];
            }
            $kelasX->users()->attach($pivots);

            $this->command->info('✓ 10 Siswa enrolled to X IPA 1');
        }

        // 5. Setup Jadwal Pelajaran (Guru Mengajar) untuk Kelas X IPA 1
        // Senin: MTK (Guru 1), BIN (Guru 2)
        // Selasa: BIG (Guru 3), IPA (Guru 1)

        $mtk = MataPelajaran::where('kode_mapel', 'MTK')->first();
        $bin = MataPelajaran::where('kode_mapel', 'BIN')->first();
        $big = MataPelajaran::where('kode_mapel', 'BIG')->first();
        $ipa = MataPelajaran::where('kode_mapel', 'IPA')->first();

        $dataMengajar = [
            [
                'hari' => 'Senin', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30',
                'mapel_id' => $mtk->id, 'guru_id' => $gurus[0]->id ?? null
            ],
            [
                'hari' => 'Senin', 'jam_mulai' => '08:45', 'jam_selesai' => '10:15',
                'mapel_id' => $bin->id, 'guru_id' => $gurus[1]->id ?? null
            ],
            [
                'hari' => 'Selasa', 'jam_mulai' => '07:00', 'jam_selesai' => '08:30',
                'mapel_id' => $big->id, 'guru_id' => $gurus[2]->id ?? null
            ],
            [
                'hari' => 'Selasa', 'jam_mulai' => '08:45', 'jam_selesai' => '10:45',
                'mapel_id' => $ipa->id, 'guru_id' => $gurus[0]->id ?? null
            ],
        ];

        foreach ($dataMengajar as $jm) {
            if ($jm['guru_id']) {
                GuruMengajar::create([
                    'guru_id' => $jm['guru_id'],
                    'kelas_id' => $kelasX->id,
                    'mata_pelajaran_id' => $jm['mapel_id'],
                    'tahun_ajaran' => $akademik->tahun_ajaran,
                    'semester' => $akademik->semester,
                    'hari' => $jm['hari'],
                    'jam_mulai' => $jm['jam_mulai'],
                    'jam_selesai' => $jm['jam_selesai'],
                ]);
            }
        }
        $this->command->info('✓ Jadwal Pelajaran Created for X IPA 1');
    }
}
