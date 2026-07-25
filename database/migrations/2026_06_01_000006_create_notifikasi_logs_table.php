<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Entitas: Notifikasi_Log (Tabel 1.5 No.6)
 * Menyimpan log pengiriman notifikasi WhatsApp (Fonnte): jenis, nomor tujuan,
 * isi pesan, status kirim, respon API, dan waktu kirim.
 * (Memenuhi F-08 Notifikasi WhatsApp Gateway)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->nullable()
                  ->constrained('pemesanans')->nullOnDelete();
            $table->enum('jenis', [
                'konfirmasi_pemesanan',
                'konfirmasi_admin',
                'reminder_pembayaran',
                'reminder_h3',
                'reminder_h1',
                'manual',
                'lainnya',
            ])->default('lainnya');
            $table->string('tujuan', 30);                       // nomor WA tujuan
            $table->text('pesan');
            $table->enum('status', ['terkirim', 'gagal', 'terjadwal'])->default('terjadwal');
            $table->text('response')->nullable();               // respon mentah dari Fonnte
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi_logs');
    }
};
