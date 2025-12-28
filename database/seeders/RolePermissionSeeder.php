<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar Permission Fokus E-Learning
        $permissions = [
            // 1. Manajemen User (Admin)
            'kelola pengguna',      // CRUD User (Guru/Siswa)

            // 2. Data Akademik Dasar (Admin)
            'kelola kelas',         // Setup Kelas
            'kelola mapel',         // Setup Mata Pelajaran
            'kelola tahun ajaran',  // Setup Tahun Ajaran

            // 3. Konten Pembelajaran (Guru)
            'lihat materi',
            'kelola materi',        // Upload/Edit Materi
            'unduh materi',         // Siswa download

            // 4. Aktivitas Belajar (Guru & Siswa)
            'lihat tugas',
            'kelola tugas',         // Guru buat tugas
            'kumpulkan tugas',      // Siswa upload tugas
            'nilai tugas',          // Guru nilai tugas

            // 5. Evaluasi (Kuis & Ujian)
            'lihat kuis',
            'kelola kuis',          // Guru buat bank soal & kuis
            'kerjakan kuis',        // Siswa
            'lihat ujian',
            'kelola ujian',         // Guru set ujian
            'kerjakan ujian',       // Siswa

            // 6. Monitoring (Guru & Admin)
            'kelola absensi',       // Guru buka/tutup absen
            'isi absensi',          // Siswa absen
            'lihat rekap absensi',  // Report
            'lihat rekap nilai',    // Report
        ];

        // Buat Permission
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ==========================================
        // CREATE ROLES & ASSIGN PERMISSIONS
        // ==========================================

        // 1. ADMIN
        // Fokus: Setup Sistem, User, Data Master
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // 2. GURU
        // Fokus: Mengajar (Upload Materi, Tugas, Kuis, Nilai)
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $guruRole->givePermissionTo([
            'lihat materi', 'kelola materi',
            'lihat tugas', 'kelola tugas', 'nilai tugas',
            'lihat kuis', 'kelola kuis',
            'lihat ujian', 'kelola ujian',
            'kelola absensi', 'lihat rekap absensi',
            'lihat rekap nilai'
        ]);

        // 3. SISWA
        // Fokus: Belajar (Akses Materi, Kerjakan Tugas/Kuis, Absen)
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);
        $siswaRole->givePermissionTo([
            'lihat materi', 'unduh materi',
            'lihat tugas', 'kumpulkan tugas',
            'lihat kuis', 'kerjakan kuis',
            'lihat ujian', 'kerjakan ujian',
            'isi absensi',
            'lihat rekap absensi', // hanya miliknya
            'lihat rekap nilai'    // hanya miliknya
        ]);

        $this->command->info('✓ Setup Role & Permission E-Learning Selesai.');
        $this->command->info('  Fokus: Materi, Tugas, Kuis, Ujian, Absensi.');
    }
}
