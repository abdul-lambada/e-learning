<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanPertemuan extends Model
{
    use HasFactory;

    protected $table = 'catatan_pertemuan';

    protected $fillable = [
        'user_id',
        'pertemuan_id',
        'konten',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }
}
