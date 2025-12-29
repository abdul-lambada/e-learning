<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pertemuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pertemuan';

    protected $fillable = [
        'guru_mengajar_id',
        'pertemuan_ke',
        'judul_pertemuan',
        'deskripsi',
        'tanggal_pertemuan',
        'jam_mulai',
        'jam_selesai',
        'status',
        'catatan',
        'aktif',
    ];

    protected $casts = [
        'tanggal_pertemuan' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'aktif' => 'boolean',
        'pertemuan_ke' => 'integer',
    ];

    // Relationships
    public function guruMengajar()
    {
        return $this->belongsTo(GuruMengajar::class);
    }

    public function materiPembelajaran()
    {
        return $this->hasMany(MateriPembelajaran::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function kuis()
    {
        return $this->hasMany(Kuis::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function diskusi()
    {
        return $this->hasMany(Diskusi::class)->whereNull('parent_id')->latest();
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
