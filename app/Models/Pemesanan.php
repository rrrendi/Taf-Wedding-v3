<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pemesanan extends Model
{
    protected $fillable = [
        'kode', 'user_id', 'nama_pria', 'nama_wanita', 'phone', 'email',
        'tanggal_acara', 'jumlah_tamu', 'lokasi', 'catatan', 'status', 'total',
    ];

    protected $casts = [
        'tanggal_acara' => 'date',
        'total'         => 'decimal:2',
    ];

    /* ============ RELASI ============ */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function layanans(): BelongsToMany
    {
        return $this->belongsToMany(Layanan::class, 'layanan_pemesanan')
                    ->withPivot(['qty', 'harga', 'subtotal'])
                    ->withTimestamps();
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function jadwal(): HasOne
    {
        return $this->hasOne(Jadwal::class);
    }

    public function notifikasiLogs(): HasMany
    {
        return $this->hasMany(NotifikasiLog::class);
    }

    /* ============ AKSESOR KEUANGAN (logika terpusat, anti-mislogic) ============ */

    /** Nama pasangan untuk ditampilkan, mis. "Rina & Andi". */
    public function getNamaKlienAttribute(): string
    {
        return trim($this->nama_wanita . ' & ' . $this->nama_pria, ' &');
    }

    /** Total yang sudah dibayar = jumlah pembayaran berstatus TERVERIFIKASI saja. */
    public function getTerbayarAttribute(): float
    {
        return (float) $this->pembayarans
            ->where('status', 'terverifikasi')
            ->sum('jumlah');
    }

    /** Sisa yang masih harus dibayar (tidak pernah negatif). */
    public function getSisaAttribute(): float
    {
        return max(0, (float) $this->total - $this->terbayar);
    }

    /**
     * Status pembayaran turunan:
     * - 'belum' bila belum ada pembayaran terverifikasi
     * - 'lunas' bila terbayar >= total (dan total > 0)
     * - 'dp'    bila sebagian sudah dibayar
     */
    public function getStatusBayarAttribute(): string
    {
        if ($this->total > 0 && $this->terbayar >= $this->total) {
            return 'lunas';
        }
        return $this->terbayar > 0 ? 'dp' : 'belum';
    }

    public function getStatusBayarLabelAttribute(): string
    {
        return match ($this->status_bayar) {
            'lunas' => 'Lunas',
            'dp'    => 'DP',
            default => 'Belum Bayar',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dikonfirmasi' => 'Dikonfirmasi',
            'selesai'      => 'Selesai',
            'dibatalkan'   => 'Dibatalkan',
            default        => 'Menunggu Konfirmasi',
        };
    }

    /* ============ FORMAT RUPIAH (dipakai di view & invoice) ============ */
    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total, 0, ',', '.');
    }

    public function getTerbayarFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->terbayar, 0, ',', '.');
    }

    public function getSisaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->sisa, 0, ',', '.');
    }

    /* ============ HELPER ============ */

    /** Menghitung ulang total dari subtotal layanan terpilih. */
    public function hitungUlangTotal(): void
    {
        $this->total = (float) $this->layanans()->sum('subtotal');
        $this->saveQuietly();
    }

    /** Generator kode pemesanan unik berurutan: TW-0001, TW-0002, ... */
    public static function generateKode(): string
    {
        $next = (static::max('id') ?? 0) + 1;
        return 'TW-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
