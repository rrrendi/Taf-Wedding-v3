<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel pivot (Many-to-Many) antara Pemesanan dan Layanan.
 * Satu pemesanan bisa memilih banyak layanan; menyimpan snapshot harga
 * agar laporan keuangan tetap akurat walau harga layanan berubah kemudian.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan_pemesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->constrained('pemesanans')->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('layanans')->cascadeOnDelete();
            $table->unsignedInteger('qty')->default(1);
            $table->decimal('harga', 15, 2)->default(0);     // snapshot harga saat dipesan
            $table->decimal('subtotal', 15, 2)->default(0);  // qty * harga
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan_pemesanan');
    }
};
