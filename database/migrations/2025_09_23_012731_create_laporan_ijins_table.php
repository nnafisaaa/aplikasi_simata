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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ✅ relasi ke user
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); // ✅ relasi ke unit
            $table->unsignedTinyInteger('bulan');     // bulan laporan (1-12)
            $table->unsignedSmallInteger('tahun');    // tahun laporan
            $table->integer('total_ijin')->default(0); // total jumlah ijin
            $table->timestamps();

            // ✅ biar unik per user + unit + bulan + tahun
            $table->unique(['user_id', 'unit_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_ijins');
    }
};
