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
        Schema::create('foto_absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absensi_id')->constrained('absensi')->onDelete('cascade');
            $table->string('path_foto');
            $table->enum('jenis_foto', ['masuk', 'keluar', 'verifikasi'])->default('masuk');
            $table->dateTime('waktu_foto');

            // Metadata foto
            $table->string('device_camera')->nullable();
            $table->string('resolusi')->nullable();
            $table->bigInteger('ukuran_file')->nullable(); // Dalam bytes

            // Face recognition (opsional untuk fitur lanjutan)
            $table->boolean('wajah_terdeteksi')->default(false);
            $table->decimal('confidence_score', 5, 2)->nullable(); // Skor kepercayaan face recognition
            $table->json('face_data')->nullable(); // Data face recognition

            // Verifikasi manual
            $table->boolean('diverifikasi')->default(false);
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan_verifikasi')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_absensi');
    }
};
