<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerpustakaanMateri extends Model
{
    use HasFactory;

    protected $table = 'perpustakaan_materi';
    protected $fillable = ['judul', 'deskripsi', 'kategori', 'file_path', 'url_external', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
