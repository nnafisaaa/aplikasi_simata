<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('ijins', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // nama orang yang izin
            $table->unsignedBigInteger('unit_id'); // relasi ke tabel units
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key ke tabel units
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
        });
    }

    /**
     * Balikkan migrasi (hapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('ijins');
    }
};
