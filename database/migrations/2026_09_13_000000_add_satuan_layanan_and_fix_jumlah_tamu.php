<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Jawaban atas pertanyaan: "Estimasi Jumlah Tamu" pada form pemesanan
 * sebelumnya hanya label rentang bebas (mis. "100 – 300 orang") yang
 * TIDAK dipakai untuk hitung harga sama sekali.
 *
 * Migrasi ini menghubungkan jumlah tamu dengan harga secara logis:
 *  1) layanans.satuan -> menandai layanan mana yang harganya dihitung
 *     PER ORANG (mis. Catering) vs layanan PAKET (harga tetap, tidak
 *     terpengaruh jumlah tamu, mis. Dekorasi/Foto & Video/Makeup).
 *  2) pemesanans.jumlah_tamu -> diubah dari string bebas menjadi angka
 *     pasti (integer), supaya bisa dipakai sebagai pengali harga
 *     (qty) untuk layanan yang satuan-nya 'per_orang'.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->enum('satuan', ['paket', 'per_orang'])
                  ->default('paket')
                  ->after('harga');
        });

        Schema::table('pemesanans', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_tamu')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->dropColumn('satuan');
        });

        Schema::table('pemesanans', function (Blueprint $table) {
            $table->string('jumlah_tamu')->nullable()->change();
        });
    }
};
