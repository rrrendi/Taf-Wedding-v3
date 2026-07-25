<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Client;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIK (tanpa login)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing');

/*
|--------------------------------------------------------------------------
| PENGALIHAN SETELAH LOGIN (berdasarkan role)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| AREA KLIEN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cek-tanggal', [Client\PemesananController::class, 'cekTanggal'])->name('cek.tanggal');

    Route::prefix('pesanan-saya')->name('client.')->group(function () {
        Route::get('/', [Client\PemesananController::class, 'index'])->name('pemesanan.index');
        Route::get('/buat', [Client\PemesananController::class, 'create'])->name('pemesanan.create');
        Route::post('/', [Client\PemesananController::class, 'store'])->name('pemesanan.store');
        Route::get('/{pemesanan}/sukses', [Client\PemesananController::class, 'sukses'])->name('pemesanan.sukses');
        Route::get('/{pemesanan}', [Client\PemesananController::class, 'show'])->name('pemesanan.show');
        Route::post('/{pemesanan}/bayar', [Client\PembayaranController::class, 'store'])->name('pembayaran.store');
        Route::patch('/{pemesanan}/batal', [Client\PemesananController::class, 'batal'])->name('pemesanan.batal');
        Route::delete('/{pemesanan}', [Client\PemesananController::class, 'destroy'])->name('pemesanan.destroy');
    });

    Route::get('/invoice/{pemesanan}/{mode?}', [InvoiceController::class, 'show'])->name('invoice.show');
});

/*
|--------------------------------------------------------------------------
| AREA ADMIN (role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Pemesanan
    Route::get('/pemesanan', [Admin\PemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('/pemesanan/{pemesanan}', [Admin\PemesananController::class, 'show'])->name('pemesanan.show');
    Route::patch('/pemesanan/{pemesanan}/status', [Admin\PemesananController::class, 'updateStatus'])->name('pemesanan.status');
    Route::post('/pemesanan/{pemesanan}/wa', [Admin\PemesananController::class, 'kirimWa'])->name('pemesanan.wa');
    Route::delete('/pemesanan/{pemesanan}', [Admin\PemesananController::class, 'destroy'])->name('pemesanan.destroy');

    // Pembayaran
    Route::post('/pemesanan/{pemesanan}/pembayaran', [Admin\PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::patch('/pembayaran/{pembayaran}/verify', [Admin\PembayaranController::class, 'verify'])->name('pembayaran.verify');
    Route::delete('/pembayaran/{pembayaran}', [Admin\PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

    // Jadwal (kalender)
    Route::get('/jadwal', [Admin\JadwalController::class, 'index'])->name('jadwal.index');

    // Keuangan
    Route::get('/keuangan', [Admin\KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('/keuangan/laporan', [Admin\KeuanganController::class, 'laporanPdf'])->name('keuangan.laporan');

    // Notifikasi WhatsApp (pengaturan reminder + riwayat)
    Route::get('/notifikasi', [Admin\NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi', [Admin\NotifikasiController::class, 'update'])->name('notifikasi.update');

    // Layanan (CRUD)
    Route::resource('layanan', Admin\LayananController::class)->except(['show']);

    // Galeri landing (CRUD)
    Route::get('/galeri', [Admin\GaleriController::class, 'index'])->name('galeri.index');
    Route::post('/galeri', [Admin\GaleriController::class, 'store'])->name('galeri.store');
    Route::get('/galeri/{galeri}/edit', [Admin\GaleriController::class, 'edit'])->name('galeri.edit');
    Route::put('/galeri/{galeri}', [Admin\GaleriController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{galeri}', [Admin\GaleriController::class, 'destroy'])->name('galeri.destroy');
});

require __DIR__ . '/auth.php';
