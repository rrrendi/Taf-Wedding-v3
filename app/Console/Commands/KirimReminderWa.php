<?php

namespace App\Console\Commands;

use App\Models\Pemesanan;
use App\Models\Pengaturan;
use App\Services\FonnteService;
use Illuminate\Console\Command;

/**
 * Pengingat WhatsApp otomatis (F-08) — kini DAPAT DIATUR admin lewat
 * halaman "Notifikasi WhatsApp":
 *   - Aktif / nonaktif.
 *   - H-minus reminder acara (mis. 7,3,1) — bebas ditentukan admin.
 *   - Jam pengiriman.
 *   - Reminder pembayaran (untuk acara dalam X hari) — aktif/nonaktif.
 *
 * Dijadwalkan SETIAP JAM (lihat routes/console.php); command memeriksa
 * apakah jam saat ini = jam yang dipilih admin. Jalankan paksa kapan saja:
 *   php artisan wa:reminder --now
 */
class KirimReminderWa extends Command
{
    protected $signature = 'wa:reminder {--now : Kirim sekarang, abaikan jam pengaturan}';
    protected $description = 'Kirim pengingat WhatsApp otomatis sesuai pengaturan admin';

    public function handle(FonnteService $wa): int
    {
        $aktif = Pengaturan::bool('reminder_aktif', true);
        $jam   = (int) Pengaturan::get('reminder_jam', 9);

        if (! $this->option('now') && now()->hour !== $jam) {
            return self::SUCCESS; // belum waktunya
        }

        $hariIni = now()->startOfDay();

        // --- Reminder acara H-minus (dapat diatur) ---
        if ($aktif) {
            foreach (Pengaturan::reminderHari() as $h) {
                $tanggal = $hariIni->copy()->addDays($h);

                Pemesanan::where('status', 'dikonfirmasi')
                    ->whereDate('tanggal_acara', $tanggal)
                    ->get()
                    ->each(function (Pemesanan $p) use ($wa, $h) {
                        $jenis = $h === 1 ? 'reminder_h1' : ($h === 3 ? 'reminder_h3' : 'reminder_hari');
                        $wa->kirim($p->phone, $wa->pesanReminderHari($p, $h), $jenis, $p);
                        $this->info("Reminder H-{$h} -> {$p->kode} ({$p->nama_klien})");
                    });
            }
        }

        // --- Reminder pembayaran (dapat diatur) ---
        if (Pengaturan::bool('reminder_bayar_aktif', true)) {
            $dalam = (int) Pengaturan::get('reminder_bayar_dalam', 14);
            $batas = $hariIni->copy()->addDays(max(1, $dalam));

            Pemesanan::where('status', 'dikonfirmasi')
                ->whereBetween('tanggal_acara', [$hariIni, $batas])
                ->with('pembayarans')
                ->get()
                ->filter(fn (Pemesanan $p) => $p->sisa > 0)
                ->each(function (Pemesanan $p) use ($wa) {
                    $wa->kirim($p->phone, $wa->pesanReminderPembayaran($p), 'reminder_pembayaran', $p);
                    $this->info("Reminder bayar -> {$p->kode} (sisa Rp " . number_format($p->sisa, 0, ',', '.') . ')');
                });
        }

        $this->info('Selesai memproses pengingat WhatsApp.');

        return self::SUCCESS;
    }
}
