<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoalUjian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'soal_ujian';

    protected $fillable = [
        'ujian_id',
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
        'kategori',
        'tingkat_kesulitan',
    ];

    protected $casts = [
        'nomor_soal' => 'integer',
        'bobot_nilai' => 'decimal:2',
    ];

    // Relationships
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function detailJawaban()
    {
        return $this->hasMany(DetailJawabanUjian::class);
    }

    // Scopes
    public function scopeTingkatKesulitan($query, $tingkat)
    {
        return $query->where('tingkat_kesulitan', $tingkat);
    }
}
