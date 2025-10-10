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

            // 🔹 Ganti 'nama' jadi relasi user_id
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // 🔹 Relasi unit tetap
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade');

            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Opsional: kalau mau cegah duplikat ijin dalam 1 hari per user
            // $table->unique(['user_id', 'tanggal']);
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
