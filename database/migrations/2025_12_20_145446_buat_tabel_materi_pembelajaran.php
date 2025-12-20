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
        Schema::create('materi_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->onDelete('cascade');
            $table->string('judul_materi');
            $table->text('deskripsi')->nullable();
            $table->longText('konten')->nullable(); // Konten text/HTML
            $table->enum('tipe_materi', ['video', 'dokumen', 'gambar', 'link', 'teks'])->default('teks');

            // Untuk video
            $table->string('video_url')->nullable(); // URL YouTube atau path file video
            $table->enum('video_source', ['youtube', 'upload'])->nullable();

            // Untuk dokumen
            $table->string('file_path')->nullable(); // Path file PPT, PDF, Word
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable(); // ppt, pdf, doc, docx
            $table->bigInteger('file_size')->nullable(); // Dalam bytes

            // Untuk gambar
            $table->string('gambar_path')->nullable();

            // Untuk link eksternal
            $table->string('link_url')->nullable();

            $table->integer('urutan')->default(1);
            $table->integer('durasi_estimasi')->default(0); // Dalam menit
            $table->boolean('dapat_diunduh')->default(true);
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
        Schema::dropIfExists('materi_pembelajaran');
    }
};
