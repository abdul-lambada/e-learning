<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@elearning.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'peran' => 'admin',
            'jenis_kelamin' => 'L',
            'no_telepon' => '081234567890',
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        $this->command->info('✓ Admin created: admin@elearning.com / password');

        // Guru Users
        $guru1 = User::create([
            'nama_lengkap' => 'Budi Santoso, S.Pd',
            'email' => 'budi.santoso@elearning.com',
            'username' => 'budi.santoso',
            'password' => Hash::make('password'),
            'peran' => 'guru',
            'nip' => '197801012005011001',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Pendidikan No. 123, Jakarta',
            'no_telepon' => '081234567891',
            'tanggal_lahir' => '1978-01-01',
            'tempat_lahir' => 'Jakarta',
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $guru1->assignRole('guru');

        $guru2 = User::create([
            'nama_lengkap' => 'Siti Nurhaliza, S.Pd',
            'email' => 'siti.nurhaliza@elearning.com',
            'username' => 'siti.nurhaliza',
            'password' => Hash::make('password'),
            'peran' => 'guru',
            'nip' => '198205152006042002',
            'jenis_kelamin' => 'P',
            'alamat' => 'Jl. Guru No. 45, Bandung',
            'no_telepon' => '081234567892',
            'tanggal_lahir' => '1982-05-15',
            'tempat_lahir' => 'Bandung',
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $guru2->assignRole('guru');

        $guru3 = User::create([
            'nama_lengkap' => 'Ahmad Hidayat, S.Kom',
            'email' => 'ahmad.hidayat@elearning.com',
            'username' => 'ahmad.hidayat',
            'password' => Hash::make('password'),
            'peran' => 'guru',
            'nip' => '198509202008011003',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Teknologi No. 78, Surabaya',
            'no_telepon' => '081234567893',
            'tanggal_lahir' => '1985-09-20',
            'tempat_lahir' => 'Surabaya',
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $guru3->assignRole('guru');

        $this->command->info('✓ 3 Guru created');

        // Siswa Users
        $siswaData = [
            [
                'nama_lengkap' => 'Andi Wijaya',
                'email' => 'andi.wijaya@student.com',
                'username' => 'andi.wijaya',
                'nis' => '2024001',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-03-15',
                'tempat_lahir' => 'Jakarta',
            ],
            [
                'nama_lengkap' => 'Dewi Lestari',
                'email' => 'dewi.lestari@student.com',
                'username' => 'dewi.lestari',
                'nis' => '2024002',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2008-06-20',
                'tempat_lahir' => 'Bandung',
            ],
            [
                'nama_lengkap' => 'Rizki Ramadhan',
                'email' => 'rizki.ramadhan@student.com',
                'username' => 'rizki.ramadhan',
                'nis' => '2024003',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-01-10',
                'tempat_lahir' => 'Surabaya',
            ],
            [
                'nama_lengkap' => 'Putri Ayu',
                'email' => 'putri.ayu@student.com',
                'username' => 'putri.ayu',
                'nis' => '2024004',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2008-08-25',
                'tempat_lahir' => 'Yogyakarta',
            ],
            [
                'nama_lengkap' => 'Fajar Nugroho',
                'email' => 'fajar.nugroho@student.com',
                'username' => 'fajar.nugroho',
                'nis' => '2024005',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-11-05',
                'tempat_lahir' => 'Semarang',
            ],
            [
                'nama_lengkap' => 'Maya Sari',
                'email' => 'maya.sari@student.com',
                'username' => 'maya.sari',
                'nis' => '2024006',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2008-04-18',
                'tempat_lahir' => 'Medan',
            ],
            [
                'nama_lengkap' => 'Dimas Pratama',
                'email' => 'dimas.pratama@student.com',
                'username' => 'dimas.pratama',
                'nis' => '2024007',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-07-12',
                'tempat_lahir' => 'Malang',
            ],
            [
                'nama_lengkap' => 'Intan Permata',
                'email' => 'intan.permata@student.com',
                'username' => 'intan.permata',
                'nis' => '2024008',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2008-09-30',
                'tempat_lahir' => 'Palembang',
            ],
            [
                'nama_lengkap' => 'Yoga Aditya',
                'email' => 'yoga.aditya@student.com',
                'username' => 'yoga.aditya',
                'nis' => '2024009',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2008-02-14',
                'tempat_lahir' => 'Denpasar',
            ],
            [
                'nama_lengkap' => 'Lina Marlina',
                'email' => 'lina.marlina@student.com',
                'username' => 'lina.marlina',
                'nis' => '2024010',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2008-12-22',
                'tempat_lahir' => 'Makassar',
            ],
        ];

        foreach ($siswaData as $data) {
            $siswa = User::create(array_merge($data, [
                'password' => Hash::make('password'),
                'peran' => 'siswa',
                'alamat' => 'Alamat ' . $data['nama_lengkap'],
                'no_telepon' => '0812' . rand(10000000, 99999999),
                'aktif' => true,
                'email_verified_at' => now(),
            ]));
            $siswa->assignRole('siswa');
        }

        $this->command->info('✓ 10 Siswa created');
        $this->command->info('');
        $this->command->info('=================================');
        $this->command->info('TOTAL USERS CREATED: 14');
        $this->command->info('=================================');
        $this->command->info('Admin: 1');
        $this->command->info('Guru: 3');
        $this->command->info('Siswa: 10');
        $this->command->info('');
        $this->command->info('Default password for all users: password');
        $this->command->info('');
    }
}
