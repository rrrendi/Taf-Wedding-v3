<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('icon', 16)->default('💍');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable(); // DITAMBAHKAN: Untuk path gambar
            $table->decimal('harga', 15, 2)->default(0);
            $table->enum('kategori', ['paket_wedding', 'makeup_only', 'tambahan'])->default('paket_wedding');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};