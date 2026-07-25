<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Entitas: Pembayaran (Tabel 1.5 No.4)
 * Menyimpan data pembayaran per pemesanan: jenis (DP/pelunasan), jumlah,
 * tanggal bayar, bukti transfer, dan status verifikasi.
 * (Memenuhi F-06 Pencatatan Pembayaran)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->constrained('pemesanans')->cascadeOnDelete();
            $table->enum('jenis', ['dp', 'pelunasan', 'cicilan'])->default('dp');
            $table->decimal('jumlah', 15, 2);
            $table->string('metode')->nullable();             // BCA / SeaBank / DANA / dll
            $table->dateTime('tanggal_bayar');
            $table->string('bukti')->nullable();              // path file bukti transfer
            $table->enum('status', ['menunggu', 'terverifikasi', 'ditolak'])
                  ->default('menunggu');
            $table->string('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
