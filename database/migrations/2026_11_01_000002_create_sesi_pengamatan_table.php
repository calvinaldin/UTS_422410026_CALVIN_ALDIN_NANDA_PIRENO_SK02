<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_pengamatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sesi', 20)->unique();
            $table->string('nama_sesi');
            $table->string('objek_sasaran');
            $table->foreignId('kategori_id')->constrained('kategori')->restrictOnDelete();
            $table->date('tanggal_pengamatan');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->enum('status', ['direncanakan', 'berlangsung', 'selesai', 'dibatalkan'])->default('direncanakan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('tanggal_pengamatan');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_pengamatan');
    }
};
