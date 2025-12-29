<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kelas';

    protected $fillable = [
        'kode_kelas',
        'nama_kelas',
        'tingkat',
        'jurusan',
        'tahun_ajaran',
        'kapasitas',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'kapasitas' => 'integer',
        'tingkat' => 'string',
    ];

    // Relationships


    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'kelas_siswa', 'kelas_id', 'siswa_id')
            ->withPivot('tahun_ajaran', 'semester')
            ->withTimestamps();
    }

    public function guruMengajar()
    {
        return $this->hasMany(GuruMengajar::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
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

    public function scopeTingkat($query, $tingkat)
    {
        return $query->where('tingkat', $tingkat);
    }

    public function scopeJurusan($query, $jurusan)
    {
        return $query->where('jurusan', $jurusan);
    }
}
