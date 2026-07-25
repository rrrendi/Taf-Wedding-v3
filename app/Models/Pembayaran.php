<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $fillable = [
        'pemesanan_id', 'jenis', 'jumlah', 'metode',
        'tanggal_bayar', 'bukti', 'status', 'catatan',
    ];

    protected $casts = [
        'jumlah'        => 'decimal:2',
        'tanggal_bayar' => 'datetime',
    ];

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'pelunasan' => 'Pelunasan',
            'cicilan'   => 'Cicilan',
            default     => 'DP (Down Payment)',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'terverifikasi' => 'Terverifikasi',
            'ditolak'       => 'Ditolak',
            default         => 'Menunggu Verifikasi',
        };
    }
}
