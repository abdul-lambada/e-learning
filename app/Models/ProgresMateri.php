<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgresMateri extends Model
{
    use HasFactory;

    protected $table = 'progres_materi';

    protected $fillable = [
        'user_id',
        'materi_id',
        'selesai',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'selesai' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materi()
    {
        return $this->belongsTo(MateriPembelajaran::class, 'materi_id');
    }
}
