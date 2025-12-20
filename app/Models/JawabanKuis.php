<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JawabanKuis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jawaban_kuis';

    protected $fillable = [
        'kuis_id',
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
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'lulus' => 'boolean',
        'percobaan_ke' => 'integer',
        'durasi_detik' => 'integer',
        'jumlah_benar' => 'integer',
        'jumlah_salah' => 'integer',
        'tidak_dijawab' => 'integer',
        'nilai' => 'decimal:2',
    ];

    // Relationships
    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function detailJawaban()
    {
        return $this->hasMany(DetailJawabanKuis::class);
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
}
