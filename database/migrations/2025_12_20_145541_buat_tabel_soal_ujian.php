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
        Schema::create('soal_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujian')->onDelete('cascade');
            $table->integer('nomor_soal');
            $table->longText('pertanyaan');
            $table->string('gambar_soal')->nullable();
            $table->enum('tipe_soal', ['pilihan_ganda', 'essay', 'benar_salah'])->default('pilihan_ganda');

            // Untuk pilihan ganda
            $table->text('pilihan_a')->nullable();
            $table->text('pilihan_b')->nullable();
            $table->text('pilihan_c')->nullable();
            $table->text('pilihan_d')->nullable();
            $table->text('pilihan_e')->nullable();

            // Gambar untuk setiap pilihan (opsional)
            $table->string('gambar_a')->nullable();
            $table->string('gambar_b')->nullable();
            $table->string('gambar_c')->nullable();
            $table->string('gambar_d')->nullable();
            $table->string('gambar_e')->nullable();

            // Kunci jawaban
            $table->enum('kunci_jawaban', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->text('kunci_jawaban_essay')->nullable();

            // Penilaian dan pembahasan
            $table->decimal('bobot_nilai', 5, 2)->default(1.00);
            $table->longText('pembahasan')->nullable();
            $table->string('gambar_pembahasan')->nullable();

            // Metadata
            $table->string('kategori')->nullable(); // C1-C6 (Bloom's Taxonomy)
            $table->enum('tingkat_kesulitan', ['mudah', 'sedang', 'sulit'])->default('sedang');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['ujian_id', 'nomor_soal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_ujian');
    }
};
