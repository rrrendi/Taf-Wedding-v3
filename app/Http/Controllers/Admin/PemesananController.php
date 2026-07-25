<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function __construct(private FonnteService $wa) {}

    /** F-04: Daftar seluruh pemesanan. Pencarian & halaman ditangani sisi-klien (tanpa reload). */
    public function index()
    {
        $pemesanans = Pemesanan::with(['layanans', 'pembayarans'])
            ->latest()
            ->limit(500)
            ->get();

        return view('admin.pemesanan.index', compact('pemesanans'));
    }

    /** F-04: Detail satu pemesanan (untuk verifikasi, catat bayar, ubah status). */
    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load(['layanans', 'pembayarans' => fn ($q) => $q->latest(), 'jadwal', 'user']);

        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    /**
     * F-04 + F-05: Ubah status pemesanan.
     * Saat dikonfirmasi -> cek bentrok jadwal lalu buat jadwal.
     */
    public function updateStatus(Request $request, Pemesanan $pemesanan, FonnteService $wa)
    {
        $request->validate([
            'status' => ['required', 'in:pending,dikonfirmasi,selesai,dibatalkan'],
        ]);

        $status = $request->status;

        if ($status === 'dikonfirmasi') {
            $bentrok = Pemesanan::where('status', 'dikonfirmasi')
                ->where('id', '!=', $pemesanan->id)
                ->whereDate('tanggal_acara', $pemesanan->tanggal_acara)
                ->first();

            if ($bentrok && ! $request->boolean('paksa')) {
                return back()->with('warning',
                    "Tanggal " . $pemesanan->tanggal_acara->translatedFormat('d F Y')
                    . " sudah terisi oleh pesanan {$bentrok->kode} ({$bentrok->nama_klien}). "
                    . "Tekan \"Tetap Konfirmasi\" bila Anda yakin.")
                    ->with('konfirmasi_paksa', true);
            }

            $pemesanan->jadwal()->updateOrCreate(
                ['pemesanan_id' => $pemesanan->id],
                [
                    'tanggal_mulai'   => $pemesanan->tanggal_acara,
                    'tanggal_selesai' => $pemesanan->tanggal_acara,
                    'keterangan'      => 'Acara ' . $pemesanan->nama_klien,
                ]
            );

            $pemesanan->update(['status' => 'dikonfirmasi']);

            $wa->kirim($pemesanan->phone, $wa->pesanKonfirmasiAdmin($pemesanan), 'konfirmasi_admin', $pemesanan);

            return back()->with('success', "Pesanan {$pemesanan->kode} dikonfirmasi & jadwal dibuat. WA terkirim.");
        }

        if ($status === 'dibatalkan') {
            $pemesanan->jadwal()->delete();
        }

        $pemesanan->update(['status' => $status]);

        return back()->with('success', "Status pesanan {$pemesanan->kode} diubah menjadi " . $pemesanan->status_label . '.');
    }

    /** F-08: Kirim notifikasi WhatsApp manual. */
    public function kirimWa(Request $request, Pemesanan $pemesanan, FonnteService $wa)
    {
        $jenis = $request->input('jenis', 'manual');

        $pesan = match ($jenis) {
            'reminder_pembayaran' => $wa->pesanReminderPembayaran($pemesanan),
            'reminder_h3'         => $wa->pesanReminderHari($pemesanan, 3),
            'reminder_h1'         => $wa->pesanReminderHari($pemesanan, 1),
            default               => $request->validate(['pesan' => 'required|string'])['pesan'],
        };

        $wa->kirim($pemesanan->phone, $pesan, $jenis, $pemesanan);

        return back()->with('success', 'Notifikasi WhatsApp telah dikirim/dicatat untuk ' . $pemesanan->nama_klien . '.');
    }

    /** F-04: Hapus pemesanan. */
    public function destroy(Pemesanan $pemesanan)
    {
        $kode = $pemesanan->kode;
        $pemesanan->delete();

        return redirect()->route('admin.pemesanan.index')
            ->with('success', "Pesanan {$kode} dihapus.");
    }
}
