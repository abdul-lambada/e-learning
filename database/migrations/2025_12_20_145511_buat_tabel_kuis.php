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
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->onDelete('cascade');
            $table->string('judul_kuis');
            $table->text('deskripsi')->nullable();
            $table->longText('instruksi')->nullable();

            // Pengaturan waktu
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->integer('durasi_menit')->default(60); // Durasi pengerjaan
            $table->boolean('tampilkan_timer')->default(true);

            // Pengaturan soal
            $table->integer('jumlah_soal')->default(0);
            $table->boolean('acak_soal')->default(false);
            $table->boolean('acak_jawaban')->default(false);
            $table->integer('nilai_maksimal')->default(100);
            $table->decimal('nilai_minimal_lulus', 5, 2)->default(75.00);

            // Pengaturan tampilan
            $table->boolean('tampilkan_nilai')->default(true);
            $table->boolean('tampilkan_pembahasan')->default(false);
            $table->boolean('tampilkan_kunci_jawaban')->default(false);

            // Pengaturan akses
            $table->integer('max_percobaan')->default(1);
            $table->boolean('izinkan_kembali')->default(false); // Kembali ke soal sebelumnya

            // Import
            $table->string('file_import')->nullable(); // File Excel/Word yang diimport
            $table->dateTime('tanggal_import')->nullable();

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
        Schema::dropIfExists('kuis');
    }
};
