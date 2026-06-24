<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dim_divisi', function (Blueprint $table) {
            $table->id('divisi_key');
            $table->unsignedBigInteger('divisi_id')->unique();
            $table->string('nama_divisi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dim_divisi');
    }
};
