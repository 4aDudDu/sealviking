<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul berita
            $table->string('description')->nullable(); // Deskripsi singkat
            $table->longText('content')->nullable(); // Isi lengkap berita
            $table->string('image')->nullable(); // Path gambar banner
            $table->boolean('is_hot')->default(false); // Status HOT news
            $table->date('published_at')->nullable(); // Tanggal rilis
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};