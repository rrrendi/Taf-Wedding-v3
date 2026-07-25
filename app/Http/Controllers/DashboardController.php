<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Pengalihan setelah login berdasarkan role (F-01 hak akses berbeda).
     * Admin -> dashboard admin, Klien -> portal/riwayat pesanan.
     */
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('client.pemesanan.index');
    }
}
