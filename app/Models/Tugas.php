<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tugas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tugas';

    protected $fillable = [
        'pertemuan_id',
        'judul_tugas',
        'deskripsi',
        'instruksi',
        'upload_file',
        'upload_link',
        'upload_gambar',
        'format_file_diterima',
        'max_file_size',
        'max_jumlah_file',
        'tanggal_mulai',
        'tanggal_deadline',
        'nilai_maksimal',
        'tampilkan_nilai',
        'izinkan_terlambat',
        'pengurangan_nilai_terlambat',
        'catatan_guru',
        'aktif',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_deadline' => 'datetime',
        'upload_file' => 'boolean',
        'upload_link' => 'boolean',
        'upload_gambar' => 'boolean',
        'tampilkan_nilai' => 'boolean',
        'izinkan_terlambat' => 'boolean',
        'aktif' => 'boolean',
    ];

    // Relationships
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    // Mengambil pengumpulan spesifik user
    public function pengumpulanSiswa($userId)
    {
        return $this->hasOne(PengumpulanTugas::class)->where('siswa_id', $userId);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeSedangBerlangsung($query)
    {
        return $query->where('tanggal_mulai', '<=', now())
                     ->where('tanggal_deadline', '>=', now())
                     ->where('aktif', true);
    }
}
