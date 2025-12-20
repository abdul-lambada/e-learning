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
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');

            // Konten pengumpulan
            $table->text('keterangan')->nullable();
            $table->string('link_url')->nullable();

            // Status dan penilaian
            $table->enum('status', ['draft', 'dikumpulkan', 'dinilai', 'dikembalikan'])->default('draft');
            $table->dateTime('tanggal_dikumpulkan')->nullable();
            $table->boolean('terlambat')->default(false);
            $table->integer('hari_terlambat')->default(0);

            // Penilaian
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('feedback_guru')->nullable();
            $table->dateTime('tanggal_dinilai')->nullable();
            $table->foreignId('dinilai_oleh')->nullable()->constrained('users')->onDelete('set null');

            // Revisi
            $table->integer('revisi_ke')->default(0);
            $table->boolean('perlu_revisi')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel untuk file yang diupload
        Schema::create('file_pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengumpulan_tugas_id')->constrained('pengumpulan_tugas')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('tipe_file'); // pdf, doc, image, dll
            $table->bigInteger('ukuran_file'); // Dalam bytes
            $table->enum('jenis', ['file', 'gambar'])->default('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_pengumpulan_tugas');
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
