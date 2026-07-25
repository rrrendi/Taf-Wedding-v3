<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Konfigurasi WhatsApp Gateway (Fonnte)
    |--------------------------------------------------------------------------
    | Dokumentasi: https://docs.fonnte.com/api-send-message/
    | Endpoint POST: https://api.fonnte.com/send
    | Header: Authorization: <TOKEN>   (TANPA kata "Bearer")
    */

    // Token perangkat dari dashboard Fonnte (isi di file .env).
    'token' => env('FONNTE_TOKEN', ''),

    'endpoint' => env('FONNTE_ENDPOINT', 'https://api.fonnte.com/send'),

    'country_code' => env('FONNTE_COUNTRY_CODE', '62'),

    // Nomor WhatsApp Admin/Owner (Waode) untuk menerima notifikasi pesanan baru.
    'admin_number' => env('FONNTE_ADMIN_NUMBER', '085794366898'),

    /*
    | Mode simulasi. Bila true ATAU token kosong, pesan TIDAK dikirim, hanya
    | dicatat di tabel notifikasi_logs.
    |
    | DEFAULT kini FALSE: begitu Anda mengisi FONNTE_TOKEN di .env, pesan
    | langsung dikirim tanpa perlu menambah variabel lain. Set FONNTE_SIMULATE=true
    | hanya jika Anda sengaja ingin mode demo (tanpa mengirim).
    */
    'simulate' => env('FONNTE_SIMULATE', false),
];
