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
        Schema::create('akses_pendahuluan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pendahuluan_id')->constrained('pendahuluan')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('durasi_akses')->nullable(); // Dalam detik
            $table->integer('progress')->default(0); // 0-100%
            $table->boolean('selesai')->default(false);
            $table->timestamps();

            $table->unique(['pendahuluan_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akses_pendahuluan');
    }
};
