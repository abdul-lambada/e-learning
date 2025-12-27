<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanAplikasi extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_aplikasi';
    protected $fillable = [
        'nama_sekolah',
        'email_kontak',
        'no_telepon',
        'alamat_sekolah',
        'logo_sekolah',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
        'favicon'
    ];

    public static function getSettings()
    {
        return self::first() ?: self::create(['nama_sekolah' => 'E-Learning System']);
    }
}
