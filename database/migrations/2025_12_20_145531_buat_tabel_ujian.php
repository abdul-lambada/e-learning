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
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('kode_ujian')->unique(); // Contoh: UTS-MTK-X-2024-1
            $table->string('nama_ujian');
            $table->enum('jenis_ujian', ['UTS', 'UAS', 'Ujian Harian', 'Ujian Praktek'])->default('UTS');
            $table->text('deskripsi')->nullable();
            $table->longText('instruksi')->nullable();

            // Tahun ajaran
            $table->string('tahun_ajaran'); // 2024/2025
            $table->enum('semester', ['ganjil', 'genap']);

            // Pengaturan soal
            $table->integer('jumlah_soal')->default(0);
            $table->boolean('acak_soal')->default(true);
            $table->boolean('acak_jawaban')->default(true);
            $table->integer('nilai_maksimal')->default(100);
            $table->decimal('nilai_minimal_lulus', 5, 2)->default(75.00);

            // Pengaturan waktu
            $table->integer('durasi_menit')->default(120);
            $table->boolean('tampilkan_timer')->default(true);

            // Pengaturan tampilan
            $table->boolean('tampilkan_nilai')->default(false); // Biasanya tidak langsung ditampilkan
            $table->boolean('tampilkan_pembahasan')->default(false);
            $table->boolean('tampilkan_kunci_jawaban')->default(false);

            // Pengaturan akses
            $table->integer('max_percobaan')->default(1);
            $table->boolean('izinkan_kembali')->default(false);

            // Import soal
            $table->string('file_import')->nullable(); // File Excel/Word
            $table->dateTime('tanggal_import')->nullable();
            $table->foreignId('diimport_oleh')->nullable()->constrained('users')->onDelete('set null');

            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian');
    }
};
