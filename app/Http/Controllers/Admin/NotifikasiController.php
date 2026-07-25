<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotifikasiLog;
use App\Models\Pengaturan;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $cfg = [
            'aktif' => Pengaturan::bool('reminder_aktif', true),
            'hari' => Pengaturan::get('reminder_hari_h', '3,1'),
            'jam' => (int) Pengaturan::get('reminder_jam', 9),
            'bayar_aktif' => Pengaturan::bool('reminder_bayar_aktif', true),
            'bayar_dalam' => (int) Pengaturan::get('reminder_bayar_dalam', 14),

            // Koneksi WhatsApp Gateway (Fonnte)
            'gw_aktif' => Pengaturan::bool('fonnte_enabled', true),
            'gw_admin_number' => Pengaturan::get('fonnte_admin_number', config('fonnte.admin_number')),
            'gw_token_set' => filled(Pengaturan::get('fonnte_token')) || filled(config('fonnte.token')),
        ];

        $logs = NotifikasiLog::with('pemesanan')->latest()->take(20)->get();

        return view('admin.notifikasi.index', compact('cfg', 'logs'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'reminder_aktif' => ['nullable', 'boolean'],
            'reminder_hari_h' => ['nullable', 'string', 'max:60'],
            'reminder_jam' => ['required', 'integer', 'between:0,23'],
            'reminder_bayar_aktif' => ['nullable', 'boolean'],
            'reminder_bayar_dalam' => ['required', 'integer', 'between:1,90'],
            'fonnte_enabled' => ['nullable', 'boolean'],
            'fonnte_admin_number' => ['required', 'string', 'max:20'],
            'fonnte_token' => ['nullable', 'string', 'max:255'],
        ]);

        $hari = collect(explode(',', (string) ($data['reminder_hari_h'] ?? '')))
            ->map(fn($x) => (int) trim($x))
            ->filter(fn($x) => $x > 0)
            ->unique()->sortDesc()->implode(',');

        Pengaturan::set('reminder_aktif', $request->boolean('reminder_aktif') ? '1' : '0');
        Pengaturan::set('reminder_hari_h', $hari ?: '3,1');
        Pengaturan::set('reminder_jam', (string) $data['reminder_jam']);
        Pengaturan::set('reminder_bayar_aktif', $request->boolean('reminder_bayar_aktif') ? '1' : '0');
        Pengaturan::set('reminder_bayar_dalam', (string) $data['reminder_bayar_dalam']);

        Pengaturan::set('fonnte_enabled', $request->boolean('fonnte_enabled') ? '1' : '0');
        Pengaturan::set('fonnte_admin_number', $data['fonnte_admin_number']);
        if (filled($data['fonnte_token'] ?? null)) {
            Pengaturan::set('fonnte_token', $data['fonnte_token']);
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
