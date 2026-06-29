<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This migration resets the fact_absensi table:
     * - Clears all data from fact_absensi
     * - Resets AUTO_INCREMENT to 1
     * - Preserves table structure and foreign key relationships
     * - Does NOT modify dimension tables (dim_pegawai, dim_divisi, dim_jabatan, dim_waktu)
     */
    public function up(): void
    {
        // Method 1: Using TRUNCATE with foreign key checks
        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::statement('TRUNCATE TABLE fact_absensi');
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Exception $e) {
            // Fallback to DELETE + ALTER if TRUNCATE fails
            DB::statement('DELETE FROM fact_absensi');
            DB::statement('ALTER TABLE fact_absensi AUTO_INCREMENT=1');
        }
    }

    /**
     * Reverse the migrations.
     *
     * Note: Data cannot be recovered after truncate/delete in migrations.
     * This rollback is a no-op for safety.
     */
    public function down(): void
    {
        // Intentionally empty - we don't restore deleted data
        // This is by design to prevent accidental data restoration
    }
};
