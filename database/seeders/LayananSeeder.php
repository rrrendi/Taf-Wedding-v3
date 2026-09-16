<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // 9 layanan Taf Wedding (sesuai proposal & UI) + 1 layanan tambahan untuk acara Non-Wedding.
        //
        // 'satuan' menentukan cara hitung harga di form pemesanan:
        // - 'paket'     : harga tetap, berapa pun jumlah tamu (mis. Dekorasi, Foto & Video, Makeup).
        // - 'per_orang' : harga dikalikan Estimasi Jumlah Tamu yang diisi klien (khusus Catering,
        //                 karena hanya biaya konsumsi yang secara nyata mengikuti jumlah tamu undangan).
        //                 Nilai 'harga' untuk baris ini berarti harga PER ORANG, bukan harga paket.
        $data = [
            ['nama' => 'Makeup Pengantin', 'icon' => '💄', 'kategori' => 'makeup_only',   'satuan' => 'paket',     'harga' => 3500000,  'deskripsi' => 'Riasan profesional untuk tampilan sempurna di hari istimewa.'],
            ['nama' => 'Dekorasi',         'icon' => '🌸', 'kategori' => 'paket_wedding', 'satuan' => 'paket',     'harga' => 15000000, 'deskripsi' => 'Konsep dekorasi elegan sesuai tema pernikahan Anda.'],
            ['nama' => 'Hiburan',          'icon' => '🎵', 'kategori' => 'tambahan',      'satuan' => 'paket',     'harga' => 5000000,  'deskripsi' => 'Live music, MC, dan entertainment pilihan terbaik.'],
            ['nama' => 'Foto & Video',     'icon' => '📸', 'kategori' => 'paket_wedding', 'satuan' => 'paket',     'harga' => 8000000,  'deskripsi' => 'Dokumentasi sinematik yang mengabadikan setiap momen.'],
            ['nama' => 'Upacara Adat',     'icon' => '🏛️', 'kategori' => 'tambahan',      'satuan' => 'paket',     'harga' => 4000000,  'deskripsi' => 'Tata cara adat yang khidmat dan sesuai tradisi.'],
            ['nama' => 'Catering',         'icon' => '🍽️', 'kategori' => 'paket_wedding', 'satuan' => 'per_orang', 'harga' => 50000,    'deskripsi' => 'Menu pilihan berkualitas untuk seluruh tamu undangan. Harga per orang, otomatis dikalikan Estimasi Jumlah Tamu yang Anda isi.'],
            ['nama' => 'Sound System',     'icon' => '🎙️', 'kategori' => 'tambahan',      'satuan' => 'paket',     'harga' => 3000000,  'deskripsi' => 'Sistem audio profesional untuk seluruh area venue.'],
            ['nama' => 'Siraman',          'icon' => '💧', 'kategori' => 'tambahan',      'satuan' => 'paket',     'harga' => 2500000,  'deskripsi' => 'Prosesi siraman yang sakral dengan sentuhan modern.'],
            ['nama' => 'Hias Hantaran',    'icon' => '🎁', 'kategori' => 'tambahan',      'satuan' => 'paket',     'harga' => 1500000,  'deskripsi' => 'Hantaran cantik yang dirancang penuh keindahan.'],
            ['nama' => 'Makeup Non-Wedding', 'icon' => '💋', 'kategori' => 'makeup_only', 'satuan' => 'paket',     'harga' => 2000000,  'deskripsi' => 'Riasan profesional untuk acara non-wedding: ulang tahun, wisuda, photoshoot, dan acara lainnya.'],
        ];

        foreach ($data as $row) {
            Layanan::updateOrCreate(['nama' => $row['nama']], $row + ['is_active' => true]);
        }
    }
}


