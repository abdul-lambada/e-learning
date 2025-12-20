<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kuis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kuis';

    protected $fillable = [
        'pertemuan_id',
        'judul_kuis',
        'deskripsi',
        'instruksi',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_menit',
        'tampilkan_timer',
        'jumlah_soal',
        'acak_soal',
        'acak_jawaban',
        'nilai_maksimal',
        'nilai_minimal_lulus',
        'tampilkan_nilai',
        'tampilkan_pembahasan',
        'tampilkan_kunci_jawaban',
        'max_percobaan',
        'izinkan_kembali',
        'file_import',
        'tanggal_import',
        'aktif',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tanggal_import' => 'datetime',
        'tampilkan_timer' => 'boolean',
        'acak_soal' => 'boolean',
        'acak_jawaban' => 'boolean',
        'tampilkan_nilai' => 'boolean',
        'tampilkan_pembahasan' => 'boolean',
        'tampilkan_kunci_jawaban' => 'boolean',
        'izinkan_kembali' => 'boolean',
        'aktif' => 'boolean',
        'durasi_menit' => 'integer',
        'jumlah_soal' => 'integer',
        'nilai_maksimal' => 'integer',
        'max_percobaan' => 'integer',
        'nilai_minimal_lulus' => 'decimal:2',
    ];

    // Relationships
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function soalKuis()
    {
        return $this->hasMany(SoalKuis::class);
    }

    public function jawabanKuis()
    {
        return $this->hasMany(JawabanKuis::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
