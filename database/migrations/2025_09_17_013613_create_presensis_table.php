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
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // user yang presensi
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); // unit tempat presensi
            $table->string('jenis_presensi'); // contoh: "Rapat Koordinasi", "Kegiatan CSR", dll
            $table->enum('status', ['datang', 'pulang']); // status presensi
            $table->date('tanggal');
            $table->time('waktu');
            $table->decimal('jarak', 8, 2)->nullable(); // jarak ke lokasi dalam km/meter
            $table->timestamps();

            // Cegah duplikasi: 1 user 1 tanggal 1 status (datang/pulang)
            $table->unique(['user_id', 'tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
