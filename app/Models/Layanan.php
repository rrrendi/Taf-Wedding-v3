<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Layanan extends Model
{
    protected $fillable = [
        'nama', 'icon', 'deskripsi', 'gambar', 'harga', 'kategori', 'is_active',
    ];

    protected $casts = [
        'harga'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function pemesanans(): BelongsToMany
    {
        return $this->belongsToMany(Pemesanan::class, 'layanan_pemesanan')
                    ->withPivot(['qty', 'harga', 'subtotal'])
                    ->withTimestamps();
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'makeup_only' => 'Makeup Only',
            'tambahan'    => 'Tambahan',
            default       => 'Paket Wedding',
        };
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->harga, 0, ',', '.');
    }

    // DITAMBAHKAN: Accessor URL Gambar
    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? Storage::url($this->gambar) : null;
    }
}