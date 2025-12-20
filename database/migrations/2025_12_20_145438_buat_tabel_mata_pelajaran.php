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

        // Tabel pivot untuk guru mengajar mata pelajaran di kelas tertentu
        Schema::create('guru_mengajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('tahun_ajaran');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->string('hari')->nullable(); // Senin, Selasa, dst
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->timestamps();

            $table->unique(['guru_id', 'mata_pelajaran_id', 'kelas_id', 'tahun_ajaran', 'semester'], 'guru_mapel_kelas_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_mengajar');
        Schema::dropIfExists('mata_pelajaran');
    }
};
