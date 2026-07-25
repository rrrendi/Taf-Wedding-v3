<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Cetak invoice satu pemesanan, meniru format invoice asli Taf Wedding.
     * Dapat diakses oleh Admin atau Klien pemilik pesanan.
     */
    public function show(Pemesanan $pemesanan, string $mode = 'download')
    {
        $user = Auth::user();
        abort_unless($user->isAdmin() || $pemesanan->user_id === $user->id, 403);

        $pemesanan->load(['layanans', 'pembayarans' => fn ($q) => $q->where('status', 'terverifikasi')]);

        // Nomor invoice: <id>/TAFWedding/<bulan Romawi>/<tahun>
        $roman = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        $noInvoice = str_pad((string) $pemesanan->id, 3, '0', STR_PAD_LEFT)
            . '/TAFWedding/' . $roman[now()->month] . '/' . now()->year;

        $pdf = Pdf::loadView('admin.invoice.show', compact('pemesanan', 'noInvoice'))
            ->setPaper('a4', 'portrait');

        $namaFile = 'Invoice-' . $pemesanan->kode . '.pdf';

        return $mode === 'stream'
            ? $pdf->stream($namaFile)       // tampil di browser
            : $pdf->download($namaFile);    // unduh
    }
}
