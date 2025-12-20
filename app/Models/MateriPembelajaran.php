<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MateriPembelajaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'materi_pembelajaran';

    protected $fillable = [
        'pertemuan_id',
        'judul_materi',
        'deskripsi',
        'konten',
        'tipe_materi',
        'video_url',
        'video_source',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'gambar_path',
        'link_url',
        'urutan',
        'durasi_estimasi',
        'dapat_diunduh',
        'aktif',
    ];

    protected $casts = [
        'dapat_diunduh' => 'boolean',
        'aktif' => 'boolean',
        'urutan' => 'integer',
        'durasi_estimasi' => 'integer',
        'file_size' => 'integer',
    ];

    // Relationships
    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeTipe($query, $tipe)
    {
        return $query->where('tipe_materi', $tipe);
    }
}
