<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->onDelete('cascade');
            $table->text('konten');
            $table->timestamps();

            $table->unique(['user_id', 'pertemuan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_pertemuan');
    }
};
