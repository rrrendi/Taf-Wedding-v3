<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    /** F-07: Laporan keuangan — rekap pemasukan, status bayar, grafik bulanan. */
    public function index(Request $request)
    {
        $tahun = (int) $request->query('tahun', now()->year);

        $pemesanans = Pemesanan::with('pembayarans')->get();

        $totalKontrak  = $pemesanans->sum('total');
        $totalDiterima = $pemesanans->sum(fn ($p) => $p->terbayar);
        $totalPiutang  = $pemesanans->sum(fn ($p) => $p->sisa);
        $jumlahLunas   = $pemesanans->filter(fn ($p) => $p->status_bayar === 'lunas')->count();

        // Grafik pemasukan per bulan (berdasarkan pembayaran terverifikasi tahun terpilih).
        $pemasukanBulanan = array_fill(1, 12, 0.0);
        Pembayaran::where('status', 'terverifikasi')
            ->whereYear('tanggal_bayar', $tahun)
            ->get()
            ->each(function ($bayar) use (&$pemasukanBulanan) {
                $pemasukanBulanan[$bayar->tanggal_bayar->month] += (float) $bayar->jumlah;
            });

        $namaBulan = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        // Log notifikasi WhatsApp terbaru (untuk panel di halaman keuangan).
        $logWa = \App\Models\NotifikasiLog::with('pemesanan')->latest()->take(8)->get();

        return view('admin.keuangan.index', compact(
            'tahun', 'pemesanans', 'totalKontrak', 'totalDiterima', 'totalPiutang',
            'jumlahLunas', 'pemasukanBulanan', 'namaBulan', 'logWa'
        ));
    }

    /** F-07: Cetak/unduh laporan keuangan per periode dalam format PDF. */
    public function laporanPdf(Request $request)
    {
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $awal  = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $akhir = $awal->copy()->endOfMonth();

        // Pembayaran terverifikasi pada periode.
        $pembayarans = Pembayaran::with('pemesanan')
            ->where('status', 'terverifikasi')
            ->whereBetween('tanggal_bayar', [$awal, $akhir])
            ->orderBy('tanggal_bayar')
            ->get();

        // Pesanan yang acaranya pada periode.
        $pemesanans = Pemesanan::with('pembayarans')
            ->whereBetween('tanggal_acara', [$awal, $akhir])
            ->orderBy('tanggal_acara')
            ->get();

        $totalPemasukan = $pembayarans->sum('jumlah');
        $totalPiutang   = $pemesanans->sum(fn ($p) => $p->sisa);

        $pdf = Pdf::loadView('admin.keuangan.laporan_pdf', compact(
            'awal', 'pembayarans', 'pemesanans', 'totalPemasukan', 'totalPiutang'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('Laporan-Keuangan-TafWedding-' . $awal->format('Y-m') . '.pdf');
    }
}
