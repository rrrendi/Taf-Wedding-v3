<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Entitas: Jadwal (Tabel 1.5 No.5)
 * Menyimpan jadwal event yang sudah terkonfirmasi (relasi ke pemesanan).
 * Dipakai dashboard kalender untuk mencegah double booking.
 * (Memenuhi F-05 Dashboard Jadwal)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->unique()->constrained('pemesanans')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->index('tanggal_mulai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
