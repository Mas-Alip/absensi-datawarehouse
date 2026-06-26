<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('foto_selfie')->nullable()->after('bukti_file');
            $table->decimal('latitude', 10, 7)->nullable()->after('foto_selfie');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->text('alamat')->nullable()->after('longitude');
            $table->string('device')->nullable()->after('alamat');
            $table->string('browser')->nullable()->after('device');
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn([
                'foto_selfie',
                'latitude',
                'longitude',
                'alamat',
                'device',
                'browser',
            ]);
        });
    }
};
