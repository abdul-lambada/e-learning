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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->onDelete('cascade');
            $table->string('judul_tugas');
            $table->longText('deskripsi');
            $table->longText('instruksi')->nullable();

            // Jenis pengumpulan
            $table->boolean('upload_file')->default(true);
            $table->boolean('upload_link')->default(false);
            $table->boolean('upload_gambar')->default(false);
            $table->string('format_file_diterima')->nullable(); // pdf,doc,docx,ppt,pptx
            $table->integer('max_file_size')->default(10240); // Dalam KB (default 10MB)
            $table->integer('max_jumlah_file')->default(1);

            // Deadline dan penilaian
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_deadline');
            $table->integer('nilai_maksimal')->default(100);
            $table->boolean('tampilkan_nilai')->default(true);

            // Pengaturan tambahan
            $table->boolean('izinkan_terlambat')->default(false);
            $table->integer('pengurangan_nilai_terlambat')->default(0); // Per hari
            $table->text('catatan_guru')->nullable();
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
        Schema::dropIfExists('tugas');
    }
};
