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
        Schema::create('jawaban_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->integer('percobaan_ke')->default(1);

            // Waktu pengerjaan
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_selesai')->nullable();
            $table->integer('durasi_detik')->nullable(); // Durasi aktual pengerjaan

            // Status
            $table->enum('status', ['sedang_dikerjakan', 'selesai', 'waktu_habis'])->default('sedang_dikerjakan');

            // Penilaian
            $table->decimal('nilai', 5, 2)->nullable();
            $table->integer('jumlah_benar')->default(0);
            $table->integer('jumlah_salah')->default(0);
            $table->integer('tidak_dijawab')->default(0);
            $table->boolean('lulus')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // Detail jawaban per soal
        Schema::create('detail_jawaban_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jawaban_kuis_id')->constrained('jawaban_kuis')->onDelete('cascade');
            $table->foreignId('soal_kuis_id')->constrained('soal_kuis')->onDelete('cascade');
            $table->enum('jawaban_dipilih', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->text('jawaban_essay')->nullable();
            $table->boolean('benar')->default(false);
            $table->decimal('nilai_diperoleh', 5, 2)->default(0);
            $table->integer('waktu_jawab_detik')->nullable(); // Waktu untuk menjawab soal ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_jawaban_kuis');
        Schema::dropIfExists('jawaban_kuis');
    }
};
