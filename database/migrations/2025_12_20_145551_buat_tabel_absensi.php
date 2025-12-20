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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');

            // Status kehadiran
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpha'])->default('alpha');

            // Waktu absensi
            $table->dateTime('waktu_absen')->nullable();
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();

            // Lokasi (opsional untuk absensi online)
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device_info')->nullable();

            // Keterangan
            $table->text('keterangan')->nullable();
            $table->string('surat_izin')->nullable(); // Path file surat izin/sakit

            // Verifikasi
            $table->boolean('terverifikasi')->default(false);
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pertemuan_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
