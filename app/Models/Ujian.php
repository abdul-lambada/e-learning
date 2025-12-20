<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ujian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ujian';

    protected $fillable = [
        'mata_pelajaran_id',
        'kelas_id',
        'kode_ujian',
        'nama_ujian',
        'jenis_ujian',
        'deskripsi',
        'instruksi',
        'tahun_ajaran',
        'semester',
        'jumlah_soal',
        'acak_soal',
        'acak_jawaban',
        'nilai_maksimal',
        'nilai_minimal_lulus',
        'durasi_menit',
        'tampilkan_timer',
        'tampilkan_nilai',
        'tampilkan_pembahasan',
        'tampilkan_kunci_jawaban',
        'max_percobaan',
        'izinkan_kembali',
        'file_import',
        'tanggal_import',
        'diimport_oleh',
        'aktif',
    ];

    protected $casts = [
        'tanggal_import' => 'datetime',
        'acak_soal' => 'boolean',
        'acak_jawaban' => 'boolean',
        'tampilkan_timer' => 'boolean',
        'tampilkan_nilai' => 'boolean',
        'tampilkan_pembahasan' => 'boolean',
        'tampilkan_kunci_jawaban' => 'boolean',
        'izinkan_kembali' => 'boolean',
        'aktif' => 'boolean',
        'jumlah_soal' => 'integer',
        'nilai_maksimal' => 'integer',
        'durasi_menit' => 'integer',
        'max_percobaan' => 'integer',
        'nilai_minimal_lulus' => 'decimal:2',
    ];

    // Relationships
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function importOleh()
    {
        return $this->belongsTo(User::class, 'diimport_oleh');
    }

    public function jadwalUjian()
    {
        return $this->hasMany(JadwalUjian::class);
    }

    public function soalUjian()
    {
        return $this->hasMany(SoalUjian::class);
    }

    public function jawabanUjian()
    {
        return $this->hasMany(JawabanUjian::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_ujian', $jenis);
    }

    public function scopeUTS($query)
    {
        return $query->where('jenis_ujian', 'UTS');
    }

    public function scopeUAS($query)
    {
        return $query->where('jenis_ujian', 'UAS');
    }
}
