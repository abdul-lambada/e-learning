<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'nama_badge' => 'Pengejar Ilmu',
                'slug' => 'pengejar-ilmu',
                'deskripsi' => 'Mempelajari 5 materi pertama.',
                'icon' => 'bx-book-reader',
                'warna' => 'blue',
            ],
            [
                'nama_badge' => 'Quiz Master',
                'slug' => 'quiz-master',
                'deskripsi' => 'Mendapatkan nilai sempurna (100) pada kuis.',
                'icon' => 'bx-trophy',
                'warna' => 'yellow',
            ],
            [
                'nama_badge' => 'Siswa Teladan',
                'slug' => 'siswa-teladan',
                'deskripsi' => 'Melakukan absensi tepat waktu 5 kali berturut-turut.',
                'icon' => 'bx-check-shield',
                'warna' => 'green',
            ],
            [
                'nama_badge' => 'Pahlawan Diskusi',
                'slug' => 'pahlawan-diskusi',
                'deskripsi' => 'Memberikan 5 komentar atau balasan di forum.',
                'icon' => 'bx-conversation',
                'warna' => 'purple',
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge);
        }
    }
}
