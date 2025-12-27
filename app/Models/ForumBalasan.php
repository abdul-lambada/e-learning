<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumBalasan extends Model
{
    use HasFactory;

    protected $table = 'forum_balasan';
    protected $fillable = ['forum_topik_id', 'user_id', 'konten'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topik()
    {
        return $this->belongsTo(ForumTopik::class, 'forum_topik_id');
    }
}
