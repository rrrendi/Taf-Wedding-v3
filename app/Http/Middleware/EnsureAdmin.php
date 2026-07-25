<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware otorisasi: hanya user dengan role "admin" yang boleh
 * mengakses area admin. (Memenuhi NF-02 Keamanan & F-01 hak akses berbeda)
 *
 * Alias 'admin' didaftarkan di bootstrap/app.php (lihat PANDUAN_INSTALASI.md).
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin Taf Wedding.');
        }

        return $next($request);
    }
}
