<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NilaiAkhir extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nilai_akhir';

    protected $fillable = [
        'siswa_id',
        'mata_pelajaran_id',
        'kelas_id',
        'komponen_nilai_id',
        'tahun_ajaran',
        'semester',
        // Pendahuluan
        'nilai_pendahuluan',
        'bobot_pendahuluan',
        'hasil_pendahuluan',
        // Absensi
        'nilai_absensi',
        'jumlah_hadir',
        'jumlah_izin',
        'jumlah_sakit',
        'jumlah_alpha',
        'total_pertemuan',
        'bobot_absensi',
        'hasil_absensi',
        // Tugas
        'nilai_tugas',
        'jumlah_tugas_dikumpulkan',
        'total_tugas',
        'bobot_tugas',
        'hasil_tugas',
        // Kuis
        'nilai_kuis',
        'jumlah_kuis_dikerjakan',
        'total_kuis',
        'bobot_kuis',
        'hasil_kuis',
        // Ujian
        'nilai_uts',
        'nilai_uas',
        'nilai_ujian',
        'bobot_ujian',
        'hasil_ujian',
        // Lainnya
        'nilai_lainnya',
        'bobot_lainnya',
        'hasil_lainnya',
        // Nilai Akhir
        'nilai_akhir',
        'nilai_huruf',
        'nilai_angka_4',
        'kkm',
        'lulus',
        'status',
        'predikat',
        // Verifikasi
        'catatan_guru',
        'terverifikasi',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
        'tanggal_perhitungan',
        'dikunci',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
        'tanggal_perhitungan' => 'datetime',
        'lulus' => 'boolean',
        'terverifikasi' => 'boolean',
        'dikunci' => 'boolean',
        // Integers
        'jumlah_hadir' => 'integer',
        'jumlah_izin' => 'integer',
        'jumlah_sakit' => 'integer',
        'jumlah_alpha' => 'integer',
        'total_pertemuan' => 'integer',
        'jumlah_tugas_dikumpulkan' => 'integer',
        'total_tugas' => 'integer',
        'jumlah_kuis_dikerjakan' => 'integer',
        'total_kuis' => 'integer',
        // Decimals
        'nilai_pendahuluan' => 'decimal:2',
        'bobot_pendahuluan' => 'decimal:2',
        'hasil_pendahuluan' => 'decimal:2',
        'nilai_absensi' => 'decimal:2',
        'bobot_absensi' => 'decimal:2',
        'hasil_absensi' => 'decimal:2',
        'nilai_tugas' => 'decimal:2',
        'bobot_tugas' => 'decimal:2',
        'hasil_tugas' => 'decimal:2',
        'nilai_kuis' => 'decimal:2',
        'bobot_kuis' => 'decimal:2',
        'hasil_kuis' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_ujian' => 'decimal:2',
        'bobot_ujian' => 'decimal:2',
        'hasil_ujian' => 'decimal:2',
        'nilai_lainnya' => 'decimal:2',
        'bobot_lainnya' => 'decimal:2',
        'hasil_lainnya' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'nilai_angka_4' => 'decimal:2',
        'kkm' => 'decimal:2',
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function komponenNilai()
    {
        return $this->belongsTo(KomponenNilai::class);
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    // Scopes
    public function scopeLulus($query)
    {
        return $query->where('lulus', true);
    }

    public function scopeTidakLulus($query)
    {
        return $query->where('lulus', false);
    }

    public function scopeTerverifikasi($query)
    {
        return $query->where('terverifikasi', true);
    }

    public function scopeDikunci($query)
    {
        return $query->where('dikunci', true);
    }

    // Helpers
    public function hitungNilaiAkhir()
    {
        $this->nilai_akhir =
            $this->hasil_pendahuluan +
            $this->hasil_absensi +
            $this->hasil_tugas +
            $this->hasil_kuis +
            $this->hasil_ujian +
            $this->hasil_lainnya;

        $this->lulus = $this->nilai_akhir >= $this->kkm;
        $this->status = $this->lulus ? 'lulus' : 'tidak_lulus';

        return $this->nilai_akhir;
    }

    public function konversiNilaiHuruf()
    {
        $nilai = $this->nilai_akhir;

        if ($nilai >= 90) return 'A';
        if ($nilai >= 85) return 'A-';
        if ($nilai >= 80) return 'B+';
        if ($nilai >= 75) return 'B';
        if ($nilai >= 70) return 'B-';
        if ($nilai >= 65) return 'C+';
        if ($nilai >= 60) return 'C';
        if ($nilai >= 55) return 'C-';
        if ($nilai >= 50) return 'D';
        return 'E';
    }

    public function konversiNilaiAngka4()
    {
        $nilai = $this->nilai_akhir;

        if ($nilai >= 90) return 4.00;
        if ($nilai >= 85) return 3.70;
        if ($nilai >= 80) return 3.30;
        if ($nilai >= 75) return 3.00;
        if ($nilai >= 70) return 2.70;
        if ($nilai >= 65) return 2.30;
        if ($nilai >= 60) return 2.00;
        if ($nilai >= 55) return 1.70;
        if ($nilai >= 50) return 1.00;
        return 0.00;
    }

    public function getPredikat()
    {
        $nilai = $this->nilai_akhir;

        if ($nilai >= 85) return 'A';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 55) return 'C';
        return 'D';
    }
}
