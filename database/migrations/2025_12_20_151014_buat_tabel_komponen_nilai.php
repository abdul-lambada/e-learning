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
        Schema::create('komponen_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->string('tahun_ajaran');
            $table->enum('semester', ['ganjil', 'genap']);

            // Bobot Penilaian (dalam persen)
            $table->decimal('bobot_pendahuluan', 5, 2)->default(10.00); // PM (Pendahuluan/Materi)
            $table->decimal('bobot_absensi', 5, 2)->default(10.00); // Abs (Absensi)
            $table->decimal('bobot_tugas', 5, 2)->default(5.00); // Tgs (Tugas)
            $table->decimal('bobot_kuis', 5, 2)->default(25.00); // Prak (Praktek/Kuis)
            $table->decimal('bobot_ujian', 5, 2)->default(40.00); // PrakAk (UTS/UAS)
            $table->decimal('bobot_lainnya', 5, 2)->default(10.00); // Komponen tambahan

            // Validasi total harus 100%
            // Total = PM + Abs + Tgs + Prak + PrakAk + Lainnya = 100%

            // KKM (Kriteria Ketuntasan Minimal)
            $table->decimal('kkm', 5, 2)->default(75.00);

            // Keterangan
            $table->text('keterangan')->nullable();
            $table->boolean('aktif')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Unique per mata pelajaran, tahun ajaran, dan semester
            $table->unique(['mata_pelajaran_id', 'tahun_ajaran', 'semester'], 'komponen_nilai_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_nilai');
    }
};
