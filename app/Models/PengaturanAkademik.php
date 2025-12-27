<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanAkademik extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_akademik';
    protected $fillable = ['tahun_ajaran', 'semester', 'is_aktif'];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public static function active()
    {
        return self::where('is_aktif', true)->first();
    }
}
