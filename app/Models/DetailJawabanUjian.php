<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJawabanUjian extends Model
{
    use HasFactory;

    protected $table = 'detail_jawaban_ujian';

    protected $fillable = [
        'jawaban_ujian_id',
        'soal_ujian_id',
        'jawaban_dipilih',
        'jawaban_essay',
        'benar',
        'nilai_diperoleh',
        'waktu_jawab_detik',
        'ragu_ragu',
    ];

    protected $casts = [
        'benar' => 'boolean',
        'ragu_ragu' => 'boolean',
        'nilai_diperoleh' => 'decimal:2',
        'waktu_jawab_detik' => 'integer',
    ];

    // Relationships
    public function jawabanUjian()
    {
        return $this->belongsTo(JawabanUjian::class);
    }

    public function soalUjian()
    {
        return $this->belongsTo(SoalUjian::class);
    }

    // Scopes
    public function scopeRaguRagu($query)
    {
        return $query->where('ragu_ragu', true);
    }
}
