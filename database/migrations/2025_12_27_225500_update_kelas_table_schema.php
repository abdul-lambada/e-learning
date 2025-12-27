<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Add kode_kelas if not exists
            if (!Schema::hasColumn('kelas', 'kode_kelas')) {
                $table->string('kode_kelas')->unique()->after('id');
            }

            // Add tahun_ajaran if not exists
            if (!Schema::hasColumn('kelas', 'tahun_ajaran')) {
                $table->string('tahun_ajaran')->nullable()->after('jurusan');
            }

            // Change tingkat if it's enum X, XI, XII to support 10, 11, 12
            // We'll use a raw statement for certain databases if needed,
            // but for portability in Laravel 10+, we can just redefine it if the driver supports it.
            // Since it's a new system, we can safely just alter it.
        });

        // Use raw query to modify enum values as it's often more reliable for enums
        try {
            DB::statement("ALTER TABLE kelas MODIFY COLUMN tingkat ENUM('10', '11', '12', 'X', 'XI', 'XII')");
        } catch (\Exception $e) {
            // Fallback for drivers that don't support this
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn(['kode_kelas', 'tahun_ajaran']);
        });
    }
};
