<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasSiswa extends Model
{
    use HasFactory;

    protected $table = 'kelas_siswa';

    protected $fillable = [
        'kelas_id',
        'siswa_id',
        'tahun_ajaran',
        'semester',
    ];

    // Relationships
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}
