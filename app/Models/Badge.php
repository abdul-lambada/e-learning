<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_badge',
        'slug',
        'deskripsi',
        'icon',
        'warna',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')
                    ->withTimestamps();
    }
}
