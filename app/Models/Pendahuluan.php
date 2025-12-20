<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendahuluan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pendahuluan';

    protected $fillable = [
        'mata_pelajaran_id',
        'judul',
        'deskripsi',
        'konten',
        'video_url',
        'file_pendukung',
        'durasi_estimasi',
        'wajib_diselesaikan',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'wajib_diselesaikan' => 'boolean',
        'aktif' => 'boolean',
        'durasi_estimasi' => 'integer',
        'urutan' => 'integer',
    ];

    // Relationships
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function aksesPendahuluan()
    {
        return $this->hasMany(AksesPendahuluan::class);
    }
}
