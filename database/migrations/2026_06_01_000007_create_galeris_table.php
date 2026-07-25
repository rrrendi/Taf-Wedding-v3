<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->string('judul');                 // label foto, mis. "Dekorasi Pelaminan"
            $table->string('gambar')->nullable();    // path file di storage (public)
            $table->string('warna')->nullable();     // gradient fallback bila belum ada foto
            $table->unsignedInteger('urutan')->default(0); // urutan tampil di landing
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};
