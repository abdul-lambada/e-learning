<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KomponenNilai extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'komponen_nilai';

    protected $fillable = [
        'mata_pelajaran_id',
        'tahun_ajaran',
        'semester',
        'bobot_pendahuluan',
        'bobot_absensi',
        'bobot_tugas',
        'bobot_kuis',
        'bobot_ujian',
        'bobot_lainnya',
        'kkm',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'bobot_pendahuluan' => 'decimal:2',
        'bobot_absensi' => 'decimal:2',
        'bobot_tugas' => 'decimal:2',
        'bobot_kuis' => 'decimal:2',
        'bobot_ujian' => 'decimal:2',
        'bobot_lainnya' => 'decimal:2',
        'kkm' => 'decimal:2',
    ];

    // Relationships
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
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

    // Helper
    public function getTotalBobot()
    {
        return $this->bobot_pendahuluan +
               $this->bobot_absensi +
               $this->bobot_tugas +
               $this->bobot_kuis +
               $this->bobot_ujian +
               $this->bobot_lainnya;
    }
}
