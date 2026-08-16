<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Jawaban atas pertanyaan: form pemesanan sebelumnya hanya melayani acara
 * Wedding (field nama mempelai pria & wanita wajib diisi). Migrasi ini
 * menambahkan pembeda jenis acara (Wedding / Non-Wedding & acara lain)
 * beserta kolom pendukungnya, sehingga klien non-wedding (mis. makeup
 * ulang tahun, wisuda, photoshoot) tetap bisa memesan tanpa dipaksa
 * mengisi data mempelai.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->enum('jenis_acara', ['wedding', 'lainnya'])
                  ->default('wedding')
                  ->after('user_id');

            // Diisi hanya ketika jenis_acara = 'lainnya', contoh: "Ulang Tahun", "Wisuda", "Photoshoot".
            $table->string('nama_acara')->nullable()->after('nama_wanita');
        });

        Schema::table('pemesanans', function (Blueprint $table) {
            // Nama mempelai wanita hanya relevan/wajib untuk acara Wedding.
            $table->string('nama_wanita')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->dropColumn(['jenis_acara', 'nama_acara']);
        });

        Schema::table('pemesanans', function (Blueprint $table) {
            $table->string('nama_wanita')->nullable(false)->change();
        });
    }
};
