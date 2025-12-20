<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMengajar extends Model
{
    use HasFactory;

    protected $table = 'guru_mengajar';

    protected $fillable = [
        'guru_id',
        'mata_pelajaran_id',
        'kelas_id',
        'tahun_ajaran',
        'semester',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Relationships
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pertemuan()
    {
        return $this->hasMany(Pertemuan::class);
    }
}
