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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas'); // Contoh: X IPA 1, XI IPS 2, XII TKJ 1
            $table->enum('tingkat', ['X', 'XI', 'XII']);
            $table->string('jurusan')->nullable(); // IPA, IPS, TKJ, RPL, MM, dll
            $table->foreignId('wali_kelas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('kapasitas')->default(36);
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel pivot untuk siswa dan kelas
        Schema::create('kelas_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->string('tahun_ajaran'); // Contoh: 2024/2025
            $table->enum('semester', ['ganjil', 'genap']);
            $table->timestamps();

            $table->unique(['kelas_id', 'siswa_id', 'tahun_ajaran', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_siswa');
        Schema::dropIfExists('kelas');
    }
};
