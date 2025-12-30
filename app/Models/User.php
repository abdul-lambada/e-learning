<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use App\Traits\EarnsPoints;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, EarnsPoints;

    protected $fillable = [
        'nama_lengkap',
        'email',
        'username',
        'password',
        'peran',
        'nis',
        'nip',
        'foto_profil',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'tanggal_lahir',
        'tempat_lahir',
        'aktif',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal_lahir' => 'date',
        'aktif' => 'boolean',
    ];

    // Relationships

    // Siswa - Kelas
    public function kelasSiswa()
    {
        return $this->hasMany(KelasSiswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'kelas_siswa', 'siswa_id', 'kelas_id')
            ->withPivot('tahun_ajaran', 'semester')
            ->withTimestamps();
    }

    // Guru - Mengajar
    public function guruMengajar()
    {
        return $this->hasMany(GuruMengajar::class, 'guru_id');
    }



    // Tugas
    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'siswa_id');
    }

    // Kuis
    public function jawabanKuis()
    {
        return $this->hasMany(JawabanKuis::class, 'siswa_id');
    }

    // Ujian
    public function jawabanUjian()
    {
        return $this->hasMany(JawabanUjian::class, 'siswa_id');
    }

    // Absensi
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    // Pendahuluan
    public function aksesPendahuluan()
    {
        return $this->hasMany(AksesPendahuluan::class, 'siswa_id');
    }

    // Nilai Akhir
    public function nilaiAkhir()
    {
        return $this->hasMany(NilaiAkhir::class, 'siswa_id');
    }

    // Scopes
    public function scopeAdmin($query)
    {
        return $query->where('peran', 'admin');
    }

    public function scopeGuru($query)
    {
        return $query->where('peran', 'guru');
    }

    public function scopeSiswa($query)
    {
        return $query->where('peran', 'siswa');
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    // Helpers
    public function isAdmin()
    {
        return $this->peran === 'admin';
    }

    public function isGuru()
    {
        return $this->peran === 'guru';
    }

    public function isSiswa()
    {
        return $this->peran === 'siswa';
    }

    public function awardBadge($slug)
    {
        $badge = Badge::where('slug', $slug)->first();
        if ($badge && !$this->badges()->where('badge_id', $badge->id)->exists()) {
            $this->badges()->attach($badge->id);

            // Berikan poin bonus untuk badge
            $this->awardPoints(50, "Mendapatkan lencana: {$badge->nama_badge}");

            return true;
        }
        return false;
    }

    public function progressMateri()
    {
        return $this->hasMany(ProgresMateri::class);
    }

    public function catatanPertemuan()
    {
        return $this->hasMany(CatatanPertemuan::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
    }

    public function updateStreak()
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        if ($this->last_activity_date === $today) {
            return;
        }

        if ($this->last_activity_date === $yesterday) {
            $this->increment('streak_count');
        } else {
            $this->streak_count = 1;
        }

        $this->last_activity_date = $today;
        $this->save();
    }
}
