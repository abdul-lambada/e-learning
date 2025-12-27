<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumTopik extends Model
{
    use HasFactory;

    protected $table = 'forum_topik';
    protected $fillable = ['guru_mengajar_id', 'pertemuan_id', 'user_id', 'judul', 'konten', 'pinned'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guruMengajar()
    {
        return $this->belongsTo(GuruMengajar::class);
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function balasan()
    {
        return $this->hasMany(ForumBalasan::class, 'forum_topik_id');
    }
}
