<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fact_absensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_key');
            $table->unsignedBigInteger('divisi_key');
            $table->unsignedBigInteger('jabatan_key');
            $table->unsignedBigInteger('waktu_key');
            $table->unsignedSmallInteger('total_hadir')->default(0);
            $table->unsignedSmallInteger('total_telat')->default(0);
            $table->unsignedSmallInteger('total_izin')->default(0);
            $table->unsignedSmallInteger('total_sakit')->default(0);
            $table->unsignedSmallInteger('total_alfa')->default(0);
            $table->timestamps();

            $table->foreign('pegawai_key')->references('pegawai_key')->on('dim_pegawai');
            $table->foreign('divisi_key')->references('divisi_key')->on('dim_divisi');
            $table->foreign('jabatan_key')->references('jabatan_key')->on('dim_jabatan');
            $table->foreign('waktu_key')->references('waktu_key')->on('dim_waktu');
            $table->unique(['pegawai_key', 'waktu_key'], 'fact_absensi_pegawai_waktu_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fact_absensi');
    }
};
