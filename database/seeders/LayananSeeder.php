<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // 9 layanan Taf Wedding (sesuai proposal & UI). Harga = ilustrasi acuan.
        $data = [
            ['nama' => 'Makeup Pengantin', 'icon' => '💄', 'kategori' => 'makeup_only',   'harga' => 3500000,  'deskripsi' => 'Riasan profesional untuk tampilan sempurna di hari istimewa.'],
            ['nama' => 'Dekorasi',         'icon' => '🌸', 'kategori' => 'paket_wedding', 'harga' => 15000000, 'deskripsi' => 'Konsep dekorasi elegan sesuai tema pernikahan Anda.'],
            ['nama' => 'Hiburan',          'icon' => '🎵', 'kategori' => 'tambahan',      'harga' => 5000000,  'deskripsi' => 'Live music, MC, dan entertainment pilihan terbaik.'],
            ['nama' => 'Foto & Video',     'icon' => '📸', 'kategori' => 'paket_wedding', 'harga' => 8000000,  'deskripsi' => 'Dokumentasi sinematik yang mengabadikan setiap momen.'],
            ['nama' => 'Upacara Adat',     'icon' => '🏛️', 'kategori' => 'tambahan',      'harga' => 4000000,  'deskripsi' => 'Tata cara adat yang khidmat dan sesuai tradisi.'],
            ['nama' => 'Catering',         'icon' => '🍽️', 'kategori' => 'paket_wedding', 'harga' => 25000000, 'deskripsi' => 'Menu pilihan berkualitas untuk seluruh tamu undangan.'],
            ['nama' => 'Sound System',     'icon' => '🎙️', 'kategori' => 'tambahan',      'harga' => 3000000,  'deskripsi' => 'Sistem audio profesional untuk seluruh area venue.'],
            ['nama' => 'Siraman',          'icon' => '💧', 'kategori' => 'tambahan',      'harga' => 2500000,  'deskripsi' => 'Prosesi siraman yang sakral dengan sentuhan modern.'],
            ['nama' => 'Hias Hantaran',    'icon' => '🎁', 'kategori' => 'tambahan',      'harga' => 1500000,  'deskripsi' => 'Hantaran cantik yang dirancang penuh keindahan.'],
        ];

        foreach ($data as $row) {
            Layanan::updateOrCreate(['nama' => $row['nama']], $row + ['is_active' => true]);
        }
    }
}
