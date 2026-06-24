<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dim_pegawai', function (Blueprint $table) {
            $table->id('pegawai_key');
            $table->unsignedBigInteger('pegawai_id')->unique();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->enum('jenis_kelamin', ['male', 'female']);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->date('tanggal_masuk')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dim_pegawai');
    }
};
