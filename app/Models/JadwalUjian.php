<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalUjian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jadwal_ujian';

    protected $fillable = [
        'ujian_id',
        'tanggal_ujian',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'pengawas',
        'pengawas_id',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    // Relationships
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function pengawasUser()
    {
        return $this->belongsTo(User::class, 'pengawas_id');
    }

    public function jawabanUjian()
    {
        return $this->hasMany(JawabanUjian::class);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
