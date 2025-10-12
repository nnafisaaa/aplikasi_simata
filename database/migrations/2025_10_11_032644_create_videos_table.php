<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel videos.
     */
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');                 // Judul video
            $table->text('description')->nullable(); // Deskripsi video (opsional)
            $table->string('youtube_url');           // URL YouTube (misalnya: https://www.youtube.com/watch?v=abc123)
            $table->timestamps();                    // created_at dan updated_at
        });
    }

    /**
     * Hapus tabel videos jika rollback dilakukan.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
