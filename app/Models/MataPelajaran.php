<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataPelajaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'deskripsi',
        'gambar_cover',
        'jumlah_pertemuan',
        'durasi_pertemuan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'jumlah_pertemuan' => 'integer',
        'durasi_pertemuan' => 'integer',
    ];

    // Relationships
    public function guruMengajar()
    {
        return $this->hasMany(GuruMengajar::class);
    }

    public function pendahuluan()
    {
        return $this->hasMany(Pendahuluan::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }

    public function komponenNilai()
    {
        return $this->hasMany(KomponenNilai::class);
    }

    public function nilaiAkhir()
    {
        return $this->hasMany(NilaiAkhir::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }
}
