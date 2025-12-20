<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengumpulanTugas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'keterangan',
        'link_url',
        'status',
        'tanggal_dikumpulkan',
        'terlambat',
        'hari_terlambat',
        'nilai',
        'feedback_guru',
        'tanggal_dinilai',
        'dinilai_oleh',
        'revisi_ke',
        'perlu_revisi',
    ];

    protected $casts = [
        'tanggal_dikumpulkan' => 'datetime',
        'tanggal_dinilai' => 'datetime',
        'terlambat' => 'boolean',
        'perlu_revisi' => 'boolean',
        'hari_terlambat' => 'integer',
        'revisi_ke' => 'integer',
        'nilai' => 'decimal:2',
    ];

    // Relationships
    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function penilai()
    {
        return $this->belongsTo(User::class, 'dinilai_oleh');
    }

    public function filePengumpulan()
    {
        return $this->hasMany(FilePengumpulanTugas::class);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeTerlambat($query)
    {
        return $query->where('terlambat', true);
    }
}
