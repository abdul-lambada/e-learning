<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesPendahuluan extends Model
{
    use HasFactory;

    protected $table = 'akses_pendahuluan';

    protected $fillable = [
        'pendahuluan_id',
        'siswa_id',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_akses',
        'progress',
        'selesai',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'selesai' => 'boolean',
        'progress' => 'integer',
        'durasi_akses' => 'integer',
    ];

    // Relationships
    public function pendahuluan()
    {
        return $this->belongsTo(Pendahuluan::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
