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
        Schema::create('pendahuluan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->longText('konten'); // Konten pendahuluan (HTML)
            $table->string('video_url')->nullable(); // URL video YouTube atau file
            $table->string('file_pendukung')->nullable(); // PDF, PPT, dll
            $table->integer('durasi_estimasi')->default(30); // Dalam menit
            $table->boolean('wajib_diselesaikan')->default(true);
            $table->integer('urutan')->default(1);
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
        Schema::dropIfExists('pendahuluan');
    }
};
