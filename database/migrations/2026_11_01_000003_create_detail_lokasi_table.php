<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_lokasi', function (Blueprint $table) {
            $table->id();
            // UNIQUE pada foreign key = menjamin relasi One-to-One
            $table->foreignId('sesi_pengamatan_id')->unique()->constrained('sesi_pengamatan')->cascadeOnDelete();
            $table->string('nama_lokasi');
            $table->decimal('lintang', 10, 7);
            $table->decimal('bujur', 10, 7);
            $table->unsignedInteger('ketinggian')->nullable();
            $table->text('catatan_cuaca')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_lokasi');
    }
};
