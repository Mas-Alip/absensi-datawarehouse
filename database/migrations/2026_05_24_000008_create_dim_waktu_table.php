<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dim_waktu', function (Blueprint $table) {
            $table->id('waktu_key');
            $table->date('tanggal')->unique();
            $table->tinyInteger('hari');
            $table->tinyInteger('bulan');
            $table->smallInteger('tahun');
            $table->string('nama_hari');
            $table->string('nama_bulan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dim_waktu');
    }
};
