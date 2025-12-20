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

        // Create Permissions (Bahasa Indonesia)
        $permissions = [
            // Manajemen Pengguna
            'lihat pengguna',
            'tambah pengguna',
            'ubah pengguna',
            'hapus pengguna',

            // Manajemen Kelas
            'lihat kelas',
            'tambah kelas',
            'ubah kelas',
            'hapus kelas',
            'kelola siswa kelas',

            // Mata Pelajaran
            'lihat mata pelajaran',
            'tambah mata pelajaran',
            'ubah mata pelajaran',
            'hapus mata pelajaran',

            // Pertemuan
            'lihat pertemuan',
            'tambah pertemuan',
            'ubah pertemuan',
            'hapus pertemuan',

            // Materi
            'lihat materi',
            'tambah materi',
            'ubah materi',
            'hapus materi',
            'unduh materi',

            // Tugas
            'lihat tugas',
            'tambah tugas',
            'ubah tugas',
            'hapus tugas',
            'kumpulkan tugas',
            'nilai tugas',

            // Kuis
            'lihat kuis',
            'tambah kuis',
            'ubah kuis',
            'hapus kuis',
            'kerjakan kuis',
            'nilai kuis',
            'import kuis',

            // Ujian
            'lihat ujian',
            'tambah ujian',
            'ubah ujian',
            'hapus ujian',
            'kerjakan ujian',
            'nilai ujian',
            'import ujian',
            'kelola jadwal ujian',

            // Absensi
            'lihat absensi',
            'tambah absensi',
            'ubah absensi',
            'hapus absensi',
            'verifikasi absensi',

            // Nilai
            'lihat nilai',
            'tambah nilai',
            'ubah nilai',
            'hapus nilai',
            'verifikasi nilai',
            'lihat nilai sendiri',

            // Pendahuluan
            'lihat pendahuluan',
            'tambah pendahuluan',
            'ubah pendahuluan',
            'hapus pendahuluan',
            'selesaikan pendahuluan',

            // Laporan
            'lihat laporan',
            'export laporan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles and Assign Permissions

        // ROLE ADMIN
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all()); // Admin memiliki semua permission

        // ROLE GURU
        $guruRole = Role::create(['name' => 'guru']);
        $guruRole->givePermissionTo([
            // Kelas
            'lihat kelas',
            'kelola siswa kelas',

            // Mata Pelajaran
            'lihat mata pelajaran',

            // Pertemuan
            'lihat pertemuan',
            'tambah pertemuan',
            'ubah pertemuan',
            'hapus pertemuan',

            // Materi
            'lihat materi',
            'tambah materi',
            'ubah materi',
            'hapus materi',

            // Tugas
            'lihat tugas',
            'tambah tugas',
            'ubah tugas',
            'hapus tugas',
            'nilai tugas',

            // Kuis
            'lihat kuis',
            'tambah kuis',
            'ubah kuis',
            'hapus kuis',
            'nilai kuis',
            'import kuis',

            // Ujian
            'lihat ujian',
            'tambah ujian',
            'ubah ujian',
            'hapus ujian',
            'nilai ujian',
            'import ujian',
            'kelola jadwal ujian',

            // Absensi
            'lihat absensi',
            'tambah absensi',
            'ubah absensi',
            'verifikasi absensi',

            // Nilai
            'lihat nilai',
            'tambah nilai',
            'ubah nilai',
            'verifikasi nilai',

            // Pendahuluan
            'lihat pendahuluan',
            'tambah pendahuluan',
            'ubah pendahuluan',
            'hapus pendahuluan',

            // Laporan
            'lihat laporan',
            'export laporan',
        ]);

        // ROLE SISWA
        $siswaRole = Role::create(['name' => 'siswa']);
        $siswaRole->givePermissionTo([
            // Kelas
            'lihat kelas',

            // Mata Pelajaran
            'lihat mata pelajaran',

            // Pertemuan
            'lihat pertemuan',

            // Materi
            'lihat materi',
            'unduh materi',

            // Tugas
            'lihat tugas',
            'kumpulkan tugas',

            // Kuis
            'lihat kuis',
            'kerjakan kuis',

            // Ujian
            'lihat ujian',
            'kerjakan ujian',

            // Absensi
            'lihat absensi',
            'tambah absensi',

            // Nilai
            'lihat nilai sendiri',

            // Pendahuluan
            'lihat pendahuluan',
            'selesaikan pendahuluan',
        ]);

        $this->command->info('✓ Roles dan Permissions berhasil dibuat!');
        $this->command->info('');
        $this->command->info('=================================');
        $this->command->info('ROLES & PERMISSIONS');
        $this->command->info('=================================');
        $this->command->info('Admin: Semua permission (' . Permission::count() . ' permissions)');
        $this->command->info('Guru: Permission mengajar dan menilai (' . $guruRole->permissions->count() . ' permissions)');
        $this->command->info('Siswa: Permission belajar dan mengumpulkan (' . $siswaRole->permissions->count() . ' permissions)');
        $this->command->info('');
    }
}
