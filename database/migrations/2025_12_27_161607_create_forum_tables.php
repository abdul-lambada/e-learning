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
        Schema::create('forum_topik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_mengajar_id')->constrained('guru_mengajar')->onDelete('cascade');
            $table->foreignId('pertemuan_id')->nullable()->constrained('pertemuan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('konten');
            $table->boolean('pinned')->default(false);
            $table->timestamps();
        });

        Schema::create('forum_balasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_topik_id')->constrained('forum_topik')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('konten');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forum_balasan');
        Schema::dropIfExists('forum_topik');
    }
};
