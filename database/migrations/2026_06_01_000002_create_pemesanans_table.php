<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Entitas: Pemesanan (Tabel 1.5 No.3)
 * Menyimpan data pemesanan klien: relasi ke user, tanggal acara, lokasi,
 * jumlah tamu, catatan khusus, status, dan total kontrak.
 * (Memenuhi F-02 Form Pemesanan & F-04 Manajemen Data Pemesanan)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();                 // contoh: TW-0001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('nama_pria');
            $table->string('nama_wanita');
            $table->string('phone', 20);                          // WA untuk notifikasi
            $table->string('email')->nullable();

            $table->date('tanggal_acara');
            $table->string('jumlah_tamu')->nullable();            // contoh: "100 – 300 orang"
            $table->string('lokasi');                             // venue / gedung
            $table->text('catatan')->nullable();

            $table->enum('status', ['pending', 'dikonfirmasi', 'selesai', 'dibatalkan'])
                  ->default('pending');
            $table->decimal('total', 15, 2)->default(0);          // total kontrak (Rp)

            $table->timestamps();

            $table->index('tanggal_acara');                       // bantu cek bentrok jadwal
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
