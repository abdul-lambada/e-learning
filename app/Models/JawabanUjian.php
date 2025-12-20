<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JawabanUjian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jawaban_ujian';

    protected $fillable = [
        'ujian_id',
        'jadwal_ujian_id',
        'siswa_id',
        'percobaan_ke',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_detik',
        'status',
        'nilai',
        'jumlah_benar',
        'jumlah_salah',
        'tidak_dijawab',
        'lulus',
        'terverifikasi',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
        'catatan_pengawas',
        'catatan_sistem',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'lulus' => 'boolean',
        'terverifikasi' => 'boolean',
        'percobaan_ke' => 'integer',
        'durasi_detik' => 'integer',
        'jumlah_benar' => 'integer',
        'jumlah_salah' => 'integer',
        'tidak_dijawab' => 'integer',
        'nilai' => 'decimal:2',
    ];

    // Relationships
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function jadwalUjian()
    {
        return $this->belongsTo(JadwalUjian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function detailJawaban()
    {
        return $this->hasMany(DetailJawabanUjian::class);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeLulus($query)
    {
        return $query->where('lulus', true);
    }

    public function scopeTerverifikasi($query)
    {
        return $query->where('terverifikasi', true);
    }
}
