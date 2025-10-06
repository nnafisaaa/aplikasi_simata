<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_ijins', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // nama karyawan/siswa
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); 
            $table->unsignedTinyInteger('bulan');     // bulan laporan (1-12)
            $table->unsignedSmallInteger('tahun');    // tahun laporan
            $table->integer('total_ijin')->default(0); // total jumlah ijin
            $table->timestamps();

            // Biar ga ada data duplikat untuk orang yang sama dalam bulan+tahun
            $table->unique(['nama', 'unit_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_ijins');
    }
};
