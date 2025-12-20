<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('komponen_nilai_id')->constrained('komponen_nilai')->onDelete('cascade');
            $table->string('tahun_ajaran');
            $table->enum('semester', ['ganjil', 'genap']);

            // ===== NILAI KOMPONEN (0-100) =====

            // 1. PM (Pendahuluan/Materi) - 10%
            $table->decimal('nilai_pendahuluan', 5, 2)->default(0); // Dari akses_pendahuluan
            $table->decimal('bobot_pendahuluan', 5, 2)->default(10.00);
            $table->decimal('hasil_pendahuluan', 5, 2)->default(0); // nilai × bobot / 100

            // 2. Abs (Absensi) - 10%
            $table->decimal('nilai_absensi', 5, 2)->default(0); // Dari persentase kehadiran
            $table->integer('jumlah_hadir')->default(0);
            $table->integer('jumlah_izin')->default(0);
            $table->integer('jumlah_sakit')->default(0);
            $table->integer('jumlah_alpha')->default(0);
            $table->integer('total_pertemuan')->default(16);
            $table->decimal('bobot_absensi', 5, 2)->default(10.00);
            $table->decimal('hasil_absensi', 5, 2)->default(0); // nilai × bobot / 100

            // 3. Tgs (Tugas) - 5%
            $table->decimal('nilai_tugas', 5, 2)->default(0); // Rata-rata dari semua tugas
            $table->integer('jumlah_tugas_dikumpulkan')->default(0);
            $table->integer('total_tugas')->default(0);
            $table->decimal('bobot_tugas', 5, 2)->default(5.00);
            $table->decimal('hasil_tugas', 5, 2)->default(0); // nilai × bobot / 100

            // 4. Prak (Praktek/Kuis) - 25%
            $table->decimal('nilai_kuis', 5, 2)->default(0); // Rata-rata dari semua kuis
            $table->integer('jumlah_kuis_dikerjakan')->default(0);
            $table->integer('total_kuis')->default(0);
            $table->decimal('bobot_kuis', 5, 2)->default(25.00);
            $table->decimal('hasil_kuis', 5, 2)->default(0); // nilai × bobot / 100

            // 5. PrakAk (UTS/UAS) - 40%
            $table->decimal('nilai_uts', 5, 2)->nullable(); // Nilai UTS
            $table->decimal('nilai_uas', 5, 2)->nullable(); // Nilai UAS
            $table->decimal('nilai_ujian', 5, 2)->default(0); // Rata-rata UTS & UAS
            $table->decimal('bobot_ujian', 5, 2)->default(40.00);
            $table->decimal('hasil_ujian', 5, 2)->default(0); // nilai × bobot / 100

            // 6. Lainnya (Opsional) - 10%
            $table->decimal('nilai_lainnya', 5, 2)->default(0); // Sikap, keaktifan, dll
            $table->decimal('bobot_lainnya', 5, 2)->default(10.00);
            $table->decimal('hasil_lainnya', 5, 2)->default(0); // nilai × bobot / 100

            // ===== NILAI AKHIR =====
            // Formula: (10% × PM) + (10% × Abs) + (5% × Tgs) + (25% × Prak) + (40% × PrakAk) + (10% × Lainnya)
            $table->decimal('nilai_akhir', 5, 2)->default(0);

            // Konversi Huruf
            $table->char('nilai_huruf', 2)->nullable(); // A, A-, B+, B, B-, C+, C, C-, D, E
            $table->decimal('nilai_angka_4', 3, 2)->nullable(); // Skala 4.0 (untuk rapor)

            // Status Kelulusan
            $table->decimal('kkm', 5, 2)->default(75.00);
            $table->boolean('lulus')->default(false);
            $table->enum('status', ['belum_lengkap', 'lengkap', 'lulus', 'tidak_lulus'])->default('belum_lengkap');

            // Predikat (untuk Kurikulum 2013)
            $table->enum('predikat', ['A', 'B', 'C', 'D'])->nullable();

            // Catatan & Verifikasi
            $table->text('catatan_guru')->nullable();
            $table->boolean('terverifikasi')->default(false);
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tanggal_verifikasi')->nullable();

            // Metadata
            $table->dateTime('tanggal_perhitungan')->nullable();
            $table->boolean('dikunci')->default(false); // Kunci nilai agar tidak bisa diubah

            $table->timestamps();
            $table->softDeletes();

            // Unique per siswa, mata pelajaran, tahun ajaran, semester
            $table->unique(['siswa_id', 'mata_pelajaran_id', 'tahun_ajaran', 'semester'], 'nilai_akhir_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_akhir');
    }
};
