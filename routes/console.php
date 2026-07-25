<?php

use Illuminate\Support\Facades\Schedule;

/*
| Penjadwalan command. Jalankan scheduler dengan:
|   php artisan schedule:work        (saat development / demo)
| atau pasang cron di server produksi:
|   * * * * * cd /path-project && php artisan schedule:run >> /dev/null 2>&1
|
| Command 'wa:reminder' dijalankan SETIAP JAM. Di dalam command, ia memeriksa
| apakah jam saat ini sama dengan "Jam pengiriman" yang dipilih admin pada
| halaman Notifikasi WhatsApp — sehingga waktu kirim bisa diubah tanpa
| menyentuh kode lagi.
*/
Schedule::command('wa:reminder')->hourly();
