<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perlengkapan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama');
            $table->enum('jenis', ['teleskop', 'kamera', 'filter', 'tripod', 'lainnya']);
            $table->enum('kondisi', ['baik', 'perlu_kalibrasi', 'rusak'])->default('baik');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perlengkapan');
    }
};
