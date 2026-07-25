<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Daftar layanan dulu (dibutuhkan oleh pesanan).
        $this->call(LayananSeeder::class);

        // 1b) Galeri default untuk landing page.
        $this->call(GaleriSeeder::class);

        // 2) Akun Admin / Owner (Waode).
        $admin = User::updateOrCreate(
            ['email' => 'admin@tafwedding.com'],
            [
                'name'     => 'Waode Trismawati',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'phone'    => '085794366898',
                'alamat'   => 'Taman Holis Indah, Cigondewah Rahayu, Bandung',
            ]
        );

        // 3) Akun Klien contoh.
        $klien = User::updateOrCreate(
            ['email' => 'klien@example.com'],
            [
                'name'     => 'Rina Pratiwi',
                'password' => Hash::make('password'),
                'role'     => 'klien',
                'phone'    => '081234567890',
            ]
        );

        // 4) Data pemesanan contoh (total dihitung dari layanan -> konsisten).
        $layanan = Layanan::pluck('id', 'nama'); // map nama -> id
        $L = fn (array $namaList) => collect($namaList)->map(fn ($n) => $layanan[$n])->all();

        $samples = [
            [
                'pria' => 'Andi', 'wanita' => 'Rina', 'tanggal' => '2026-06-14',
                'venue' => 'Gedung Serbaguna Bandung', 'phone' => '081234567890',
                'status' => 'dikonfirmasi', 'bayar' => 'dp', 'user_id' => $klien->id,
                'layanan' => $L(['Makeup Pengantin', 'Dekorasi', 'Foto & Video', 'Catering']),
                'tamu' => '300 – 500 orang',
            ],
            [
                'pria' => 'Budi', 'wanita' => 'Maya', 'tanggal' => '2026-06-28',
                'venue' => 'Hotel Savoy Homann', 'phone' => '081298765432',
                'status' => 'dikonfirmasi', 'bayar' => 'lunas', 'user_id' => $admin->id,
                'layanan' => $L(['Makeup Pengantin', 'Dekorasi', 'Hiburan', 'Foto & Video', 'Upacara Adat', 'Catering', 'Sound System']),
                'tamu' => '> 500 orang',
            ],
            [
                'pria' => 'Dimas', 'wanita' => 'Sari', 'tanggal' => '2026-07-12',
                'venue' => 'Padma Hotel Bandung', 'phone' => '085612345678',
                'status' => 'pending', 'bayar' => 'belum', 'user_id' => $klien->id,
                'layanan' => $L(['Makeup Pengantin', 'Dekorasi', 'Foto & Video', 'Catering', 'Siraman', 'Hias Hantaran']),
                'tamu' => '100 – 300 orang',
            ],
            [
                'pria' => 'Fajar', 'wanita' => 'Lina', 'tanggal' => '2026-07-26',
                'venue' => 'Trans Luxury Hotel', 'phone' => '087812345678',
                'status' => 'dikonfirmasi', 'bayar' => 'dp', 'user_id' => $admin->id,
                'layanan' => $L(['Makeup Pengantin', 'Dekorasi', 'Hiburan', 'Foto & Video', 'Upacara Adat', 'Catering', 'Sound System', 'Siraman', 'Hias Hantaran']),
                'tamu' => '> 500 orang',
            ],
            [
                'pria' => 'Raka', 'wanita' => 'Dewi', 'tanggal' => '2026-08-09',
                'venue' => 'The Valley Resort Bandung', 'phone' => '089912345678',
                'status' => 'pending', 'bayar' => 'belum', 'user_id' => $klien->id,
                'layanan' => $L(['Makeup Pengantin', 'Dekorasi', 'Foto & Video', 'Catering', 'Sound System']),
                'tamu' => '300 – 500 orang',
            ],
        ];

        foreach ($samples as $s) {
            $p = Pemesanan::create([
                'kode'          => Pemesanan::generateKode(),
                'user_id'       => $s['user_id'],
                'nama_pria'     => $s['pria'],
                'nama_wanita'   => $s['wanita'],
                'phone'         => $s['phone'],
                'email'         => null,
                'tanggal_acara' => $s['tanggal'],
                'jumlah_tamu'   => $s['tamu'],
                'lokasi'        => $s['venue'],
                'status'        => $s['status'],
                'total'         => 0,
            ]);

            // Lampirkan layanan + snapshot harga
            $attach = [];
            foreach (Layanan::whereIn('id', $s['layanan'])->get() as $l) {
                $attach[$l->id] = ['qty' => 1, 'harga' => $l->harga, 'subtotal' => $l->harga];
            }
            $p->layanans()->attach($attach);
            $p->hitungUlangTotal();
            $p->refresh();

            // Jadwal untuk yang dikonfirmasi
            if ($s['status'] === 'dikonfirmasi') {
                $p->jadwal()->create([
                    'tanggal_mulai'   => $p->tanggal_acara,
                    'tanggal_selesai' => $p->tanggal_acara,
                    'keterangan'      => 'Acara ' . $p->nama_klien,
                ]);
            }

            // Pembayaran konsisten dengan total
            if ($s['bayar'] === 'dp') {
                $p->pembayarans()->create([
                    'jenis' => 'dp', 'jumlah' => round($p->total * 0.5),
                    'metode' => 'BCA', 'tanggal_bayar' => now()->subDays(7),
                    'status' => 'terverifikasi', 'catatan' => 'DP 50% (data contoh)',
                ]);
            } elseif ($s['bayar'] === 'lunas') {
                $p->pembayarans()->create([
                    'jenis' => 'dp', 'jumlah' => round($p->total * 0.5),
                    'metode' => 'BCA', 'tanggal_bayar' => now()->subDays(20),
                    'status' => 'terverifikasi', 'catatan' => 'DP 50% (data contoh)',
                ]);
                $p->pembayarans()->create([
                    'jenis' => 'pelunasan', 'jumlah' => $p->total - round($p->total * 0.5),
                    'metode' => 'SeaBank', 'tanggal_bayar' => now()->subDays(3),
                    'status' => 'terverifikasi', 'catatan' => 'Pelunasan (data contoh)',
                ]);
            }
        }
    }
}
