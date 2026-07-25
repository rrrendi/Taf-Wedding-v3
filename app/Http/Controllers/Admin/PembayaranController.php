<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Services\FonnteService;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    /** F-06: Admin mencatat pembayaran DP/pelunasan (langsung terverifikasi). */
    public function store(Request $request, Pemesanan $pemesanan, FonnteService $wa)
    {
        $validated = $request->validate([
            'jenis'  => ['required', 'in:dp,pelunasan,cicilan'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'metode' => ['nullable', 'string', 'max:50'],
            'bukti'  => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $path = $request->hasFile('bukti')
            ? $request->file('bukti')->store('bukti-bayar', 'public')
            : null;

        $pemesanan->pembayarans()->create([
            'jenis'         => $validated['jenis'],
            'jumlah'        => $validated['jumlah'],
            'metode'        => $validated['metode'] ?? null,
            'tanggal_bayar' => now(), // WAKTU DICATAT REAL-TIME OLEH SERVER
            'bukti'         => $path,
            'status'        => 'terverifikasi', 
            'catatan'       => 'Dicatat oleh admin.',
        ]);

        // Refresh untuk menghitung ulang sisa/status bayar.
        $pemesanan->refresh()->load('pembayarans');

        // F-08: kirim ringkasan pembayaran ke klien.
        $wa->kirim($pemesanan->phone, $wa->pesanReminderPembayaran($pemesanan), 'reminder_pembayaran', $pemesanan);

        return back()->with('success',
            'Pembayaran tercatat. Sisa tagihan: Rp ' . number_format($pemesanan->sisa, 0, ',', '.') . '.');
    }

    /** F-06: Verifikasi / tolak bukti pembayaran yang diunggah klien. */
    public function verify(Request $request, Pembayaran $pembayaran)
    {
        $request->validate(['status' => ['required', 'in:terverifikasi,ditolak']]);

        $pembayaran->update([
            'status'  => $request->status,
            'catatan' => $request->status === 'terverifikasi'
                ? 'Diverifikasi oleh admin.'
                : 'Ditolak oleh admin.',
        ]);

        return back()->with('success', 'Status pembayaran diperbarui menjadi ' . $pembayaran->status_label . '.');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();

        return back()->with('success', 'Data pembayaran dihapus.');
    }
}