<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_presensis', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // nama karyawan/siswa
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); 
            $table->unsignedTinyInteger('bulan');   // 1-12
            $table->unsignedSmallInteger('tahun');  // ex: 2025
            $table->integer('total_hadir')->default(0);  // total presensi datang
            $table->integer('total_pulang')->default(0); // total presensi pulang
            $table->timestamps();

            // unique biar 1 orang 1 unit tidak dobel per bulan+tahun
            $table->unique(['nama', 'unit_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_presensis');
    }
};
