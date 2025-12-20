<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilePengumpulanTugas extends Model
{
    use HasFactory;

    protected $table = 'file_pengumpulan_tugas';

    protected $fillable = [
        'pengumpulan_tugas_id',
        'nama_file',
        'path_file',
        'tipe_file',
        'ukuran_file',
        'jenis',
    ];

    protected $casts = [
        'ukuran_file' => 'integer',
    ];

    // Relationships
    public function pengumpulanTugas()
    {
        return $this->belongsTo(PengumpulanTugas::class);
    }
}
