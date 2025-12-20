<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absensi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'absensi';

    protected $fillable = [
        'pertemuan_id',
        'siswa_id',
        'status',
        'waktu_absen',
        'jam_masuk',
        'jam_keluar',
        'latitude',
        'longitude',
        'ip_address',
        'device_info',
        'keterangan',
        'surat_izin',
        'terverifikasi',
        'diverifikasi_oleh',
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
        'jam_masuk' => 'datetime:H:i',
        'jam_keluar' => 'datetime:H:i',
        'terverifikasi' => 'boolean',
    ];

    // Relationships
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function fotoAbsensi()
    {
        return $this->hasMany(FotoAbsensi::class);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeHadir($query)
    {
        return $query->where('status', 'hadir');
    }

    public function scopeAlpha($query)
    {
        return $query->where('status', 'alpha');
    }

    public function scopeTerverifikasi($query)
    {
        return $query->where('terverifikasi', true);
    }
}
