<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJawabanKuis extends Model
{
    use HasFactory;

    protected $table = 'detail_jawaban_kuis';

    protected $fillable = [
        'jawaban_kuis_id',
        'soal_kuis_id',
        'jawaban_dipilih',
        'jawaban_essay',
        'benar',
        'nilai_diperoleh',
        'waktu_jawab_detik',
    ];

    protected $casts = [
        'benar' => 'boolean',
        'nilai_diperoleh' => 'decimal:2',
        'waktu_jawab_detik' => 'integer',
    ];

    // Relationships
    public function jawabanKuis()
    {
        return $this->belongsTo(JawabanKuis::class);
    }

    public function soalKuis()
    {
        return $this->belongsTo(SoalKuis::class);
    }
}
