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
        Schema::create('jawaban_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujian')->onDelete('cascade');
            $table->foreignId('jadwal_ujian_id')->nullable()->constrained('jadwal_ujian')->onDelete('set null');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->integer('percobaan_ke')->default(1);

            // Waktu pengerjaan
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->integer('durasi_detik')->nullable();

            // Status
            $table->enum('status', ['sedang_dikerjakan', 'selesai', 'waktu_habis', 'dibatalkan'])->default('sedang_dikerjakan');

            // Penilaian
            $table->decimal('nilai', 5, 2)->nullable();
            $table->integer('jumlah_benar')->default(0);
            $table->integer('jumlah_salah')->default(0);
            $table->integer('tidak_dijawab')->default(0);
            $table->boolean('lulus')->default(false);

            // Verifikasi
            $table->boolean('terverifikasi')->default(false);
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('tanggal_verifikasi')->nullable();

            // Catatan
            $table->text('catatan_pengawas')->nullable();
            $table->text('catatan_sistem')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // Detail jawaban per soal
        Schema::create('detail_jawaban_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jawaban_ujian_id')->constrained('jawaban_ujian')->onDelete('cascade');
            $table->foreignId('soal_ujian_id')->constrained('soal_ujian')->onDelete('cascade');
            $table->enum('jawaban_dipilih', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->text('jawaban_essay')->nullable();
            $table->boolean('benar')->default(false);
            $table->decimal('nilai_diperoleh', 5, 2)->default(0);
            $table->integer('waktu_jawab_detik')->nullable();
            $table->boolean('ragu_ragu')->default(false); // Ditandai ragu-ragu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_jawaban_ujian');
        Schema::dropIfExists('jawaban_ujian');
    }
};
