<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('doas', function (Blueprint $table) {
            $table->id();
            $table->string('judul');       // Judul doa, contoh: "Doa Sebelum Tidur"
            $table->text('arab');        // Teks doa dalam bahasa Arab
            $table->text('latin');         // Teks latin (transliterasi)
            $table->text('artinya');   // Terjemahan/arti
            $table->timestamps();          // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doas');
    }
};
