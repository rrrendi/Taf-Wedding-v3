<?php

namespace App\Http\Controllers;

use App\Models\Galeri;
use App\Models\Layanan;

class LandingController extends Controller
{
    /** Halaman beranda publik (landing page) — F-03 menampilkan daftar layanan + galeri. */
    public function index()
    {
        $layanans = Layanan::where('is_active', true)->orderBy('id')->get();

        $galeris = Galeri::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        return view('public.landing', compact('layanans', 'galeris'));
    }
}
