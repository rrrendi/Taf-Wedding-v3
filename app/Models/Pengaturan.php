<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Penyimpanan pengaturan sederhana (key-value), dipakai untuk
 * konfigurasi reminder WhatsApp otomatis yang dapat diubah admin.
 */
class Pengaturan extends Model
{
    protected $fillable = ['key', 'value'];

    /** Ambil nilai pengaturan; aman bila tabel belum ada (fallback ke default). */
    public static function get(string $key, $default = null)
    {
        try {
            $row = static::query()->where('key', $key)->first();
            return $row ? $row->value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }

    /** Simpan / perbarui satu pengaturan. */
    public static function set(string $key, $value): void
    {
        try {
            static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        } catch (\Throwable $e) {
            // abaikan bila tabel belum ada
        }
    }

    public static function bool(string $key, bool $default = true): bool
    {
        $v = static::get($key, $default ? '1' : '0');
        return in_array((string) $v, ['1', 'true', 'on', 'yes'], true);
    }

    /** Daftar H-minus untuk reminder acara, mis. [7,3,1]. */
    public static function reminderHari(): array
    {
        $raw = static::get('reminder_hari_h', '3,1');
        return collect(explode(',', (string) $raw))
            ->map(fn ($x) => (int) trim($x))
            ->filter(fn ($x) => $x > 0)
            ->unique()->sortDesc()->values()->all();
    }
}
