<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diskusi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'diskusi';

    protected $fillable = [
        'pertemuan_id',
        'user_id',
        'pesan',
        'parent_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function replies()
    {
        return $this->hasMany(Diskusi::class, 'parent_id')->oldest();
    }

    public function parent()
    {
        return $this->belongsTo(Diskusi::class, 'parent_id');
    }
}
