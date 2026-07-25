<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    /**
     * F-06: Klien mengunggah bukti pembayaran (DP/pelunasan).
     * Status awal "menunggu" hingga diverifikasi admin.
     */
    public function store(Request $request, Pemesanan $pemesanan, FonnteService $wa)
    {
        abort_unless($pemesanan->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'jenis'  => ['required', 'in:dp,pelunasan,cicilan'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'metode' => ['nullable', 'string', 'max:50'],
            'bukti'  => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $path = $request->file('bukti')->store('bukti-bayar', 'public');

        $pemesanan->pembayarans()->create([
            'jenis'         => $validated['jenis'],
            'jumlah'        => $validated['jumlah'],
            'metode'        => $validated['metode'] ?? null,
            'tanggal_bayar' => now(), // WAKTU DICATAT REAL-TIME OLEH SERVER
            'bukti'         => $path,
            'status'        => 'menunggu',
            'catatan'       => 'Diunggah oleh klien, menunggu verifikasi admin.',
        ]);

        // Beri tahu admin ada bukti bayar masuk.
        $wa->kirimKeAdmin(
            "💸 Bukti pembayaran masuk untuk pesanan *{$pemesanan->kode}* "
            . "({$pemesanan->nama_klien}) sebesar Rp " . number_format((float) $validated['jumlah'], 0, ',', '.')
            . ". Mohon verifikasi.",
            'lainnya',
            $pemesanan
        );

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }
}