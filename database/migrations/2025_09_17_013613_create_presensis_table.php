<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_presensi', ['datang', 'pulang']); // biar fix pilihannya
            $table->string('nama');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); 
            $table->date('tanggal');
            $table->time('waktu');
            $table->decimal('jarak', 8, 2)->nullable(); // dalam km atau meter
            $table->timestamps();

            // Biar ga dobel: 1 orang 1 unit 1 tanggal 1 jenis_presensi
            $table->unique(['nama', 'unit_id', 'tanggal', 'jenis_presensi']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
