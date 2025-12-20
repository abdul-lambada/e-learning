<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FotoAbsensi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'foto_absensi';

    protected $fillable = [
        'absensi_id',
        'path_foto',
        'jenis_foto',
        'waktu_foto',
        'device_camera',
        'resolusi',
        'ukuran_file',
        'wajah_terdeteksi',
        'confidence_score',
        'face_data',
        'diverifikasi',
        'diverifikasi_oleh',
        'catatan_verifikasi',
    ];

    protected $casts = [
        'waktu_foto' => 'datetime',
        'wajah_terdeteksi' => 'boolean',
        'diverifikasi' => 'boolean',
        'ukuran_file' => 'integer',
        'confidence_score' => 'decimal:2',
        'face_data' => 'array',
    ];

    // Relationships
    public function absensi()
    {
        return $this->belongsTo(Absensi::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    // Scopes
    public function scopeJenis($query, $jenis)
    {
        return $query->where('jenis_foto', $jenis);
    }

    public function scopeWajahTerdeteksi($query)
    {
        return $query->where('wajah_terdeteksi', true);
    }
}
