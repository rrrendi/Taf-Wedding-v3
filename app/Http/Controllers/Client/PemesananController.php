<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Layanan;
use App\Models\Pemesanan;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function __construct(private FonnteService $wa)
    {
    }

    /** F-10: Riwayat pemesanan milik klien yang sedang login (Portal Klien). */
    public function index()
    {
        $pemesanans = Auth::user()->pemesanans()
            ->with(['layanans', 'pembayarans'])
            ->latest()
            ->paginate(10);

        return view('client.pemesanan.index', compact('pemesanans'));
    }

    /** F-02 & F-03: Tampilkan form pemesanan online + daftar layanan. */
    public function create()
    {
        $aktif = Auth::user()->pemesanans()
            ->whereIn('status', ['pending', 'dikonfirmasi'])
            ->latest()
            ->first();

        if ($aktif) {
            return redirect()
                ->route('client.pemesanan.show', $aktif)
                ->with('info', 'Anda masih memiliki pemesanan aktif (Kode: ' . $aktif->kode . '). Hubungi admin bila ingin mengubah data pemesanan.');
        }

        $layanans = Layanan::where('is_active', true)->orderBy('id')->get();

        return view('client.pemesanan.create', compact('layanans'));
    }

    /** F-02: Simpan pemesanan baru + notifikasi WhatsApp (Black Box test No.3). */
    public function store(Request $request, FonnteService $wa)
    {
        $aktif = Auth::user()->pemesanans()
            ->whereIn('status', ['pending', 'dikonfirmasi'])
            ->latest()
            ->first();

        if ($aktif) {
            return redirect()
                ->route('client.pemesanan.show', $aktif)
                ->with('info', 'Anda masih memiliki pemesanan aktif (Kode: ' . $aktif->kode . ').');
        }

        $validated = $request->validate([
            'nama_pria' => ['required', 'string', 'max:100'],
            'nama_wanita' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'tanggal_acara' => ['required', 'date', 'after_or_equal:today'],
            'jumlah_tamu' => ['nullable', 'string', 'max:50'],
            'lokasi' => ['required', 'string', 'max:255'],
            'catatan' => ['nullable', 'string'],
            'layanan' => ['required', 'array', 'min:1'],
            'layanan.*' => ['integer', 'exists:layanans,id'],
        ], [
            'layanan.required' => 'Pilih minimal satu layanan.',
            'tanggal_acara.after_or_equal' => 'Tanggal acara tidak boleh di masa lalu.',
        ]);

        $pemesanan = DB::transaction(function () use ($validated) {
            $pemesanan = Pemesanan::create([
                'kode' => Pemesanan::generateKode(),
                'user_id' => Auth::id(),
                'nama_pria' => $validated['nama_pria'],
                'nama_wanita' => $validated['nama_wanita'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? Auth::user()->email,
                'tanggal_acara' => $validated['tanggal_acara'],
                'jumlah_tamu' => $validated['jumlah_tamu'] ?? null,
                'lokasi' => $validated['lokasi'],
                'catatan' => $validated['catatan'] ?? null,
                'status' => 'pending',
                'total' => 0,
            ]);

            // Lampirkan layanan terpilih + snapshot harga (anti perubahan harga).
            $layanans = Layanan::whereIn('id', $validated['layanan'])->get();
            $attach = [];
            foreach ($layanans as $l) {
                $attach[$l->id] = [
                    'qty' => 1,
                    'harga' => $l->harga,
                    'subtotal' => $l->harga,
                ];
            }
            $pemesanan->layanans()->attach($attach);
            $pemesanan->hitungUlangTotal();

            return $pemesanan;
        });

        // F-08: Notifikasi WhatsApp ke klien + ke admin/owner.
        $wa->kirim($pemesanan->phone, $wa->pesanKonfirmasiPemesanan($pemesanan), 'konfirmasi_pemesanan', $pemesanan);
        $wa->kirimKeAdmin(
            "📥 Pesanan baru *{$pemesanan->kode}* dari {$pemesanan->nama_klien} "
            . "untuk " . $pemesanan->tanggal_acara->translatedFormat('d F Y') . ". Mohon dicek.",
            'lainnya',
            $pemesanan
        );

        // Peringatan lembut bila tanggal sudah ada acara terkonfirmasi (info untuk klien).
        $bentrok = Jadwal::whereDate('tanggal_mulai', $pemesanan->tanggal_acara)->exists();

        return redirect()
            ->route('client.pemesanan.sukses', $pemesanan)
            ->with('success', 'Pemesanan berhasil dikirim! Konfirmasi telah dikirim ke WhatsApp Anda.')
            ->with(
                $bentrok ? 'info' : 'noinfo',
                'Catatan: tanggal yang Anda pilih cukup diminati. Admin akan mengonfirmasi ketersediaannya.'
            );
    }

    /** F-10: Detail/Portal satu pemesanan (timeline progress + upload bukti). */
    public function show(Pemesanan $pemesanan)
    {
        abort_unless($pemesanan->user_id === Auth::id(), 403);

        $pemesanan->load(['layanans', 'pembayarans' => fn($q) => $q->latest()]);

        return view('client.pemesanan.show', compact('pemesanan'));
    }

    /** Layar konfirmasi sesaat setelah pemesanan berhasil dikirim. */
    public function sukses(Pemesanan $pemesanan)
    {
        abort_unless($pemesanan->user_id === Auth::id(), 403);

        $pemesanan->load('layanans');

        return view('client.pemesanan.sukses', compact('pemesanan'));
    }

    /**
     * Cek ketersediaan tanggal (dipakai form via fetch/Alpine).
     * Mengembalikan JSON apakah tanggal sudah ada acara terkonfirmasi.
     */
    public function cekTanggal(Request $request)
    {
        $tanggal = $request->query('tanggal');
        $terisi = $tanggal
            ? Jadwal::whereDate('tanggal_mulai', $tanggal)->exists()
            : false;

        return response()->json(['terisi' => $terisi]);
    }

    /** Klien membatalkan pesanannya sendiri (data tetap tersimpan; DP hangus bila sudah membayar). */
    public function batal(Pemesanan $pemesanan, FonnteService $wa)
    {
        abort_unless($pemesanan->user_id === Auth::id(), 403);

        if (!in_array($pemesanan->status, ['pending', 'dikonfirmasi'])) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        $pemesanan->update(['status' => 'dibatalkan']);
        $pemesanan->jadwal()->delete(); // lepaskan tanggal pada kalender

        $wa->kirimKeAdmin(
            "❌ Pesanan *{$pemesanan->kode}* ({$pemesanan->nama_klien}) DIBATALKAN oleh klien.",
            'lainnya',
            $pemesanan
        );

        return redirect()->route('client.pemesanan.index')
            ->with('success', "Pesanan {$pemesanan->kode} telah dibatalkan.");
    }

    /**
     * Klien menghapus pesanan — HANYA diperbolehkan bila belum ada pembayaran apa pun.
     */
    public function destroy(Pemesanan $pemesanan)
    {
        abort_unless($pemesanan->user_id === Auth::id(), 403);

        if ($pemesanan->pembayarans()->count() > 0) {
            return back()->with(
                'error',
                'Pesanan yang sudah memiliki pembayaran tidak dapat dihapus. Silakan gunakan tombol "Batalkan Pesanan".'
            );
        }

        $kode = $pemesanan->kode;
        $pemesanan->delete();

        return redirect()->route('client.pemesanan.index')
            ->with('success', "Pesanan {$kode} telah dihapus.");
    }
}
