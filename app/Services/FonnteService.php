<?php

namespace App\Services;

use App\Models\NotifikasiLog;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Pengaturan;

/**
 * Layanan integrasi WhatsApp Gateway (Fonnte).
 *
 * Setiap pengiriman SELALU dicatat ke tabel notifikasi_logs sehingga
 * jejak notifikasi dapat diaudit (memenuhi entitas Notifikasi_Log & F-08).
 */
class FonnteService
{
    /**
     * Kirim pesan WhatsApp ke satu nomor dan catat log-nya.
     */
    public function kirim(
        string $tujuan,
        string $pesan,
        string $jenis = 'lainnya',
        ?Pemesanan $pemesanan = null
    ): NotifikasiLog {
        $tujuan = $this->normalisasiNomor($tujuan);

        // [ANTI-BLOKIR 1] PERSONALISASI & ID UNIK
        // Dengan menambahkan karakter unik dan waktu, HASH dari setiap teks pesan akan 100% berbeda.
        // Mencegah robot WhatsApp mendeteksi aktivitas "Copy-Paste / Spam Massal".
        $pesanUnik = $pesan . "\n\n_Ref: " . uniqid() . " • " . now()->translatedFormat('d/m/y H:i') . "_";

        $log = new NotifikasiLog([
            'pemesanan_id' => $pemesanan?->id,
            'jenis' => $jenis,
            'tujuan' => $tujuan,
            'pesan' => $pesanUnik,
            'status' => 'terjadwal',
        ]);

        $aktifGateway = Pengaturan::bool('fonnte_enabled', true);
        $token = Pengaturan::get('fonnte_token') ?: config('fonnte.token');
        $simulate = !$aktifGateway || config('fonnte.simulate') || empty($token);

        if ($simulate) {
            $log->status = 'terjadwal';
            $log->response = !$aktifGateway
                ? 'NONAKTIF — notifikasi WhatsApp dimatikan lewat menu Notifikasi WhatsApp di admin.'
                : 'MODE SIMULASI — pesan tidak dikirim ke Fonnte (token kosong / FONNTE_SIMULATE=true).';
            $log->save();
            return $log;
        }

        try {
            $response = Http::asForm()
                ->withHeaders(['Authorization' => $token])
                ->timeout(20)
                ->post(config('fonnte.endpoint'), [
                    'target' => $tujuan,
                    'message' => $pesanUnik, // Mengirim pesan yang sudah dibuat unik

                    // [ANTI-BLOKIR 2] DELAY ACAK
                    // Memberikan jeda acak 3 hingga 7 detik kepada Fonnte untuk meniru kecepatan mengetik manusia
                    'delay' => rand(3, 7),

                    'countryCode' => config('fonnte.country_code'),
                ]);

            $body = $response->json();
            $ok = $response->successful() && ($body['status'] ?? false);

            $log->status = $ok ? 'terkirim' : 'gagal';
            $log->response = $response->body();
            $log->sent_at = $ok ? now() : null;
            $log->save();
        } catch (\Throwable $e) {
            Log::error('Fonnte gagal: ' . $e->getMessage());
            $log->status = 'gagal';
            $log->response = $e->getMessage();
            $log->save();
        }

        return $log;
    }

    /** Kirim notifikasi ke nomor Admin/Owner. */
    public function kirimKeAdmin(string $pesan, string $jenis = 'lainnya', ?Pemesanan $pemesanan = null): NotifikasiLog
    {
        $nomor = Pengaturan::get('fonnte_admin_number') ?: config('fonnte.admin_number');
        return $this->kirim($nomor, $pesan, $jenis, $pemesanan);
    }

    /**
     * Normalisasi nomor: hilangkan spasi/karakter non-digit, ubah 0 awal jadi 62.
     */
    public function normalisasiNomor(string $nomor): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }
        return $nomor;
    }

    /* ===================== TEMPLATE PESAN (ANTI-BLOKIR HOOK) ===================== */
    // [ANTI-BLOKIR 3]: Memancing balasan dari Klien agar "Trust Score" WA bisnis naik.
    // Jika Klien membalas, tautan link di HP klien akan otomatis berwarna biru (bisa diklik)
    // dan nomor Anda dianggap aman / bukan penipuan oleh WhatsApp.

    public function pesanKonfirmasiPemesanan(Pemesanan $p): string
    {
        $emoji = $p->jenis_acara === 'wedding' ? '👰🤵' : '✨';

        return "Halo Kak {$p->nama_klien} {$emoji}\n\n"
            . "Terima kasih telah memesan layanan *Taf Wedding*.\n"
            . "Pesanan (Kode: *{$p->kode}*) telah masuk ke sistem dan sedang menunggu konfirmasi admin kami.\n\n"
            . "🗓️ Jenis Acara: {$p->jenis_acara_label}\n"
            . "📅 Tanggal Acara: " . $p->tanggal_acara->translatedFormat('l, d F Y') . "\n"
            . "📍 Lokasi: {$p->lokasi}\n"
            . "💰 Estimasi: Rp " . number_format((float) $p->total, 0, ',', '.') . "\n\n"
            . "💡 *PENTING:* Mohon balas pesan ini dengan mengetik *\"OK\"* agar informasi konfirmasi & tautan tagihan selanjutnya dapat Anda buka/klik. Terima kasih 🙏";
    }

    public function pesanKonfirmasiAdmin(Pemesanan $p): string
    {
        return "Halo Kak {$p->nama_klien} ✅\n\n"
            . "Kabar baik! Pesanan *{$p->kode}* Anda telah *DIKONFIRMASI* oleh Taf Wedding.\n\n"
            . "📅 " . $p->tanggal_acara->translatedFormat('l, d F Y') . "\n"
            . "📍 {$p->lokasi}\n"
            . "💰 Total Kontrak: Rp " . number_format((float) $p->total, 0, ',', '.') . "\n"
            . "💵 Sisa Tagihan: Rp " . number_format($p->sisa, 0, ',', '.') . "\n\n"
            . "Silakan lakukan pembayaran DP minimal 10% untuk mengamankan tanggal acara melalui Portal Klien.\n\n"
            . "Mohon balas *\"SIAP\"* jika pesan ini sudah diterima. 🙏";
    }

    public function pesanReminderPembayaran(Pemesanan $p): string
    {
        return "Halo Kak {$p->nama_klien} 🔔\n\n"
            . "Izin mengingatkan mengenai pembayaran untuk pesanan *{$p->kode}* (Acara "
            . $p->tanggal_acara->translatedFormat('d F Y') . ").\n\n"
            . "💰 Total: Rp " . number_format((float) $p->total, 0, ',', '.') . "\n"
            . "✅ Terbayar: Rp " . number_format($p->terbayar, 0, ',', '.') . "\n"
            . "💵 Sisa: *Rp " . number_format($p->sisa, 0, ',', '.') . "*\n\n"
            . "Pelunasan Wedding maksimal H-10 & Makeup H-2. Jika ada kendala/pertanyaan, mohon balas pesan ini ya Kak. Terima kasih 🙏";
    }

    public function pesanReminderHari(Pemesanan $p, int $h): string
    {
        $labelAcara = $p->jenis_acara === 'wedding' ? 'acara pernikahan Anda' : "acara *{$p->jenis_acara_label}* Anda";
        $penutup = $p->jenis_acara === 'wedding' ? 'Sampai jumpa di hari bahagia Anda 💍' : 'Sampai jumpa di hari acara Anda 🎉';

        return "Halo Kak {$p->nama_klien} ⏰\n\n"
            . "Semoga sehat selalu! Kami ingin mengingatkan bahwa {$labelAcara} tinggal *H-{$h}*!\n\n"
            . "📅 " . $p->tanggal_acara->translatedFormat('l, d F Y') . "\n"
            . "📍 {$p->lokasi}\n\n"
            . "Tim Taf Wedding sudah bersiap. Mohon balas *\"OK\"* agar kami tahu Kakak sudah membaca pengingat ini. {$penutup}";
    }
}