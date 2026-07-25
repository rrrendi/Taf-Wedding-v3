<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Entitas: Users (Tabel 1.5 No.1 pada proposal)
 * Menyimpan data pengguna sistem (Admin & Klien): nama, email, password,
 * nomor WhatsApp, dan role. (Memenuhi F-01 Autentikasi Pengguna)
 *
 * Catatan: file ini SENGAJA menimpa migration bawaan Laravel agar kolom
 * tambahan (role, phone, alamat) langsung tersedia saat `migrate:fresh`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // --- Kolom tambahan untuk Taf Wedding ---
            $table->enum('role', ['admin', 'klien'])->default('klien');
            $table->string('phone', 20)->nullable();   // nomor WhatsApp klien
            $table->text('alamat')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
