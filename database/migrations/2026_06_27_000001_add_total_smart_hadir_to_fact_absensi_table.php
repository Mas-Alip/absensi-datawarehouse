<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fact_absensi', function (Blueprint $table) {
            $table->unsignedSmallInteger('total_smart_hadir')->default(0)->after('total_hadir');
        });
    }

    public function down(): void
    {
        Schema::table('fact_absensi', function (Blueprint $table) {
            $table->dropColumn('total_smart_hadir');
        });
    }
};
