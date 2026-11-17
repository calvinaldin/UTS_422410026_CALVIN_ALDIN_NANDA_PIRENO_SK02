<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perlengkapan_sesi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_pengamatan_id')->constrained('sesi_pengamatan')->cascadeOnDelete();
            $table->foreignId('perlengkapan_id')->constrained('perlengkapan')->restrictOnDelete();
            $table->unsignedSmallInteger('jumlah')->default(1);
            $table->timestamps();

            $table->unique(['sesi_pengamatan_id', 'perlengkapan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perlengkapan_sesi');
    }
};
