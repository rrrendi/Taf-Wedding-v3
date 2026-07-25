<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /** F-05: Dashboard jadwal berbentuk kalender + daftar event terkonfirmasi. */
    public function index(Request $request)
    {
        // Bulan & tahun aktif (default: bulan ini). Navigasi via ?bulan=&tahun=.
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);
        $bulan = max(1, min(12, $bulan));

        $awal  = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $akhir = $awal->copy()->endOfMonth();

        // Event terkonfirmasi pada bulan terpilih, dikelompokkan per tanggal.
        $eventsBulanIni = Pemesanan::where('status', 'dikonfirmasi')
            ->whereBetween('tanggal_acara', [$awal, $akhir])
            ->orderBy('tanggal_acara')
            ->get();

        $eventsPerHari = $eventsBulanIni->groupBy(fn ($p) => $p->tanggal_acara->day);

        // Susun grid kalender (indeks kosong di awal sesuai hari pertama bulan).
        $hariPertama = $awal->dayOfWeek; // 0=Minggu ... 6=Sabtu
        $jumlahHari  = $awal->daysInMonth;

        // Seluruh event terkonfirmasi mendatang (untuk tabel di bawah kalender).
        $eventMendatang = Pemesanan::where('status', 'dikonfirmasi')
            ->whereDate('tanggal_acara', '>=', now()->startOfDay())
            ->with('layanans')
            ->orderBy('tanggal_acara')
            ->get();

        return view('admin.jadwal.index', compact(
            'bulan', 'tahun', 'awal', 'hariPertama', 'jumlahHari',
            'eventsPerHari', 'eventMendatang'
        ));
    }
}
