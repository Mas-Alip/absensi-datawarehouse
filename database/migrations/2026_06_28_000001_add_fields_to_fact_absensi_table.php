<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fact_absensi', function (Blueprint $table) {
            if (! Schema::hasColumn('fact_absensi', 'selfie_path')) {
                $table->string('selfie_path')->nullable()->after('total_smart_hadir');
            }
            if (! Schema::hasColumn('fact_absensi', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('selfie_path');
            }
            if (! Schema::hasColumn('fact_absensi', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (! Schema::hasColumn('fact_absensi', 'google_maps_url')) {
                $table->string('google_maps_url')->nullable()->after('longitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('fact_absensi', function (Blueprint $table) {
            $table->dropColumn(['selfie_path', 'latitude', 'longitude', 'google_maps_url']);
        });
    }
};
