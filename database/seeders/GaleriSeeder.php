<?php

namespace Database\Seeders;

use App\Models\Galeri;
use Illuminate\Database\Seeder;

class GaleriSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['judul' => 'Dekorasi Pelaminan', 'warna' => 'linear-gradient(135deg,#5c3d2e,#8b6547)'],
            ['judul' => 'Makeup Pengantin',   'warna' => 'linear-gradient(135deg,#4a2d4a,#7b4a6d)'],
            ['judul' => 'Outdoor Wedding',    'warna' => 'linear-gradient(135deg,#2d4a35,#4a7b5a)'],
            ['judul' => 'Resepsi Mewah',      'warna' => 'linear-gradient(135deg,#4a3d20,#8b7540)'],
            ['judul' => 'Hias Hantaran',      'warna' => 'linear-gradient(135deg,#2d354a,#4a5a7b)'],
            ['judul' => 'Siraman Adat',       'warna' => 'linear-gradient(135deg,#4a2d2d,#7b4a4a)'],
        ];

        foreach ($items as $i => $item) {
            Galeri::updateOrCreate(
                ['judul' => $item['judul']],
                [
                    'gambar'    => null,
                    'warna'     => $item['warna'],
                    'urutan'    => $i + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
