<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    /** Ringkasan operasional Taf Wedding (kartu statistik + tabel ringkas). */
    public function index()
    {
        $pemesanans = Pemesanan::with('pembayarans')->get();

        $totalPesanan = $pemesanans->count();
        $dikonfirmasi = $pemesanans->where('status', 'dikonfirmasi')->count();
        $pending = $pemesanans->where('status', 'pending')->count();

        // Pemasukan = seluruh pembayaran TERVERIFIKASI dari semua pesanan.
        $pemasukan = $pemesanans->sum(fn($p) => $p->terbayar);
        $piutang = $pemesanans->sum(fn($p) => $p->sisa);

        $pesananTerbaru = Pemesanan::with('pembayarans')->latest()->take(5)->get();

        // Tren pemasukan 7 hari terakhir, untuk grafik ringkas di Dashboard.
        $pemasukanHarian = collect(range(6, 0))->map(function ($i) {
            $tanggal = now()->subDays($i)->startOfDay();

            return [
                'label' => $tanggal->translatedFormat('d M'),
                'jumlah' => (float) Pembayaran::where('status', 'terverifikasi')
                    ->whereDate('tanggal_bayar', $tanggal)
                    ->sum('jumlah'),
            ];
        });

        return view('admin.dashboard', compact(
            'totalPesanan',
            'dikonfirmasi',
            'pending',
            'pemasukan',
            'piutang',
            'pesananTerbaru',
            'pemasukanHarian'
        ));
    }
}
