<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoalKuis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'soal_kuis';

    protected $fillable = [
        'kuis_id',
        'nomor_soal',
        'pertanyaan',
        'gambar_soal',
        'tipe_soal',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'pilihan_e',
        'gambar_a',
        'gambar_b',
        'gambar_c',
        'gambar_d',
        'gambar_e',
        'kunci_jawaban',
        'kunci_jawaban_essay',
        'bobot_nilai',
        'pembahasan',
        'gambar_pembahasan',
    ];

    protected $casts = [
        'nomor_soal' => 'integer',
        'bobot_nilai' => 'decimal:2',
    ];

    // Relationships
    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function detailJawaban()
    {
        return $this->hasMany(DetailJawabanKuis::class);
    }
}
