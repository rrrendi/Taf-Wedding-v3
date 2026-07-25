<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifikasiLog extends Model
{
    protected $fillable = [
        'pemesanan_id', 'jenis', 'tujuan', 'pesan', 'status', 'response', 'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'konfirmasi_pemesanan' => 'Konfirmasi Pemesanan',
            'konfirmasi_admin'     => 'Konfirmasi Admin',
            'reminder_pembayaran'  => 'Reminder Pembayaran',
            'reminder_h3'          => 'Reminder H-3',
            'reminder_h1'          => 'Reminder H-1',
            'manual'               => 'Pesan Manual',
            default                => 'Lainnya',
        };
    }
}
