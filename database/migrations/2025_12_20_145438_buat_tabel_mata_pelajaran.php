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
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel')->unique(); // Contoh: MTK-001, BIN-001
            $table->string('nama_mapel');
            $table->text('deskripsi')->nullable();
            $table->string('gambar_cover')->nullable();
            $table->integer('jumlah_pertemuan')->default(16); // Per semester
            $table->integer('durasi_pertemuan')->default(90); // Dalam menit
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
        Schema::dropIfExists('mata_pelajaran');
    }
};
