<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — Taf Wedding')</title>
    <link rel="icon" href="{{ asset('images/taf-emblem.png') }}">
    
    @include('partials.styles')
    
    {{-- =======================================================================
         PERBAIKAN GLOBAL CSS ADMIN
         ======================================================================= --}}
    <style>
        /* =====================================================================
           1. PERBAIKAN TANDA PANAH DROPDOWN (SELECT) KESELURUHAN ADMIN
           ===================================================================== */
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            /* Menyuntikkan panah kustom SVG warna elegan */
            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23C9A24B' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 14px center !important;
            background-size: 14px !important;
            padding-right: 40px !important;
            cursor: pointer;
        }
        /* Menghilangkan panah ganda di browser versi lama */
        select::-ms-expand {
            display: none !important;
        }

        /* =====================================================================
           2. PERBAIKAN GLOBAL TABEL ADMIN (RESPONSIVE & ANTI-CARD MENUMPUK)
           ===================================================================== */

        /* Default: ikon buka/tutup kartu disembunyikan. Cuma dimunculkan lagi
           di dalam @media(max-width:879px) di bawah — biar dijamin tidak
           pernah nongol tanpa ukuran di lebar berapa pun. */
        .rt-toggle { display: none; }
        /* Badge ringkasan (mis. status) yang ikut ditaruh di kolom pertama
           supaya kelihatan tanpa buka kartu — cuma relevan di mode kartu
           mobile, di desktop tabel sudah menampilkan kolom Status aslinya */
        .rt-status-preview { display: inline-flex; margin-left: auto; }
        
        /* DESKTOP: Rapi, sejajar kiri lurus, dilarang turun baris, lebar otomatis */
        @media (min-width: 880px) {
            .rtable, .table {
                table-layout: auto !important; 
                width: 100% !important;
            }
            .rtable th, .rtable td,
            .table th, .table td {
                text-align: left !important;
                padding: 14px 16px !important; 
                white-space: nowrap !important;
                vertical-align: middle !important;
            }
            /* Mematikan sifat margin tengah bawaan dari badge/tombol */
            .rtable td .badge, .table td .badge,
            .rtable td .pill-link, .table td .pill-link,
            .rtable td .btn, .table td .btn,
            .rtable td .flex-gap, .table td .flex-gap {
                margin: 0 !important;
                display: inline-flex !important;
                justify-content: flex-start !important;
                text-align: left !important;
            }
            /* Ikon buka/tutup kartu cuma relevan di kartu mobile — di desktop
               harus disembunyikan total, kalau tidak SVG-nya jatuh ke ukuran
               default browser (jadi raksasa & tidak proporsional) */
            .rt-toggle { display: none !important; }
            .rt-status-preview { display: none !important; }
        }

        /* MOBILE: Kartu ringkas, bisa dibuka/tutup per baris (ala Jukase) */
        @media (max-width: 879px) {
            .rtable, .table {
                display: block !important; 
                width: 100% !important;
            }
            .rtable thead, .table thead {
                display: none !important; 
            }
            .tbl-wrap {
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
            }
            .rtable tbody, .table tbody {
                display: flex !important;
                flex-direction: column !important;
                gap: 10px !important;
                padding: 14px !important;
            }
            .rtable tr, .table tr {
                display: flex !important;
                flex-direction: column !important;
                height: auto !important; 
                min-height: 0 !important;
                margin: 0 !important;
                background: #FFFFFF !important;
                border: 1.5px solid var(--border2) !important;
                border-radius: 12px !important;
                padding: 0 !important;
                overflow: hidden !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.02) !important;
            }

            /* Kolom pertama = ringkasan baris, selalu tampil, bisa di-tap untuk buka/tutup */
            .rtable tr > td:first-child, .table tr > td:first-child {
                display: flex !important;
                justify-content: flex-start !important;
                align-items: center !important;
                gap: 10px !important;
                height: auto !important;
                min-height: 0 !important;
                padding: 12px 14px !important;
                margin: 0 !important;
                line-height: 1.35 !important;
                border: none !important;
                text-align: left !important;
                white-space: normal !important;
                font-weight: 700 !important;
                cursor: pointer !important;
            }
            .rtable tr > td:first-child::before, .table tr > td:first-child::before {
                content: none !important;
            }
            .rt-toggle {
                width: 22px; height: 22px; border-radius: 50%; flex: none;
                background: var(--ink); color: var(--gold3);
                display: grid; place-items: center;
                box-shadow: 0 2px 5px -1px rgba(27,23,16,.35);
                transition: transform .25s cubic-bezier(.4,0,.2,1), background .2s, color .2s;
            }
            .rt-toggle svg { width: 11px; height: 11px; }
            .rtable tr.rt-open > td:first-child .rt-toggle,
            .table tr.rt-open > td:first-child .rt-toggle {
                transform: rotate(90deg);
                background: var(--gold-grad); color: #2A1E06;
            }

            /* Kolom sisanya hanya tampil saat baris dibuka */
            .rtable tr td:not(:first-child), .table tr td:not(:first-child) {
                display: none;
            }
            .rtable tr.rt-open td:not(:first-child), .table tr.rt-open td:not(:first-child) {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                height: auto !important; 
                min-height: 0 !important;
                padding: 9px 14px !important; 
                margin: 0 !important;
                line-height: 1.4 !important;
                border: none !important;
                border-top: 1px dashed rgba(0,0,0,0.08) !important;
                text-align: right !important; 
                white-space: normal !important; 
            }
            .rtable tr.rt-open td:last-child, .table tr.rt-open td:last-child {
                padding-bottom: 13px !important;
            }
            .rtable td::before, .table td::before {
                content: attr(data-label) !important;
                font-weight: 700 !important;
                color: var(--ink3) !important;
                font-size: 11.5px !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                margin-right: 16px !important;
                text-align: left !important;
                display: block !important;
                flex: none !important; 
            }
            /* Elemen tombol/badge didorong mentok ke kanan */
            .rtable td .badge, .table td .badge,
            .rtable td .pill-link, .table td .pill-link,
            .rtable td .btn, .table td .btn {
                margin-left: auto !important; 
                margin-right: 0 !important;
            }
            .rtable td .flex-gap, .table td .flex-gap {
                justify-content: flex-end !important;
                width: 100% !important;
                margin-top: 4px !important;
            }
            .rtable tr.rt-open td.cell-actions, .table tr.rt-open td.cell-actions {
                flex-direction: row !important;
                justify-content: flex-end !important;
            }
            .rtable tr.rt-open td.cell-actions::before, .table tr.rt-open td.cell-actions::before {
                display: none !important;
            }

            /* Baris "belum ada data" (colspan) tetap tampil polos & rata tengah */
            .rtable tr > td[colspan], .table tr > td[colspan] {
                font-weight: 400 !important;
                cursor: default !important;
                justify-content: center !important;
                text-align: center !important;
            }
            .rtable tr > td[colspan]::before, .table tr > td[colspan]::before {
                content: none !important;
            }

            /* Tabel yang barisnya sudah punya aksi klik sendiri (mis. onclick pindah halaman)
               tetap ditampilkan penuh apa adanya, tanpa mekanisme buka/tutup */
            .rtable.rtable-flat tr > td:first-child, .table.table-flat tr > td:first-child {
                font-weight: 400 !important; cursor: default !important; padding-top: 12px !important;
            }
            .rtable.rtable-flat tr > td:first-child::before, .table.table-flat tr > td:first-child::before {
                content: attr(data-label) !important; display: block !important;
            }
            .rtable.rtable-flat td:not(:first-child), .table.table-flat td:not(:first-child) {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                padding: 9px 14px !important;
                border-top: 1px dashed rgba(0,0,0,0.08) !important;
                text-align: right !important;
            }
            .rtable.rtable-flat tr > td:last-child, .table.table-flat tr > td:last-child {
                padding-bottom: 12px !important;
            }
        }
    </style>

    <script>
        /* Buka/tutup kartu tabel mobile (kolom pertama = ringkasan, tap untuk lihat detail) */
        function tafMakeTablesResponsive() {
            document.querySelectorAll('.rtable:not(.rtable-flat), .table:not(.table-flat)').forEach(function (table) {
                table.querySelectorAll('tbody tr').forEach(function (tr) {
                    if (tr.children.length <= 1) return; // lewati baris "belum ada data" (colspan)
                    if (tr.hasAttribute('onclick')) return; // baris ini sudah punya aksi klik sendiri

                    var firstCell = tr.children[0];
                    if (firstCell && !firstCell.querySelector('.rt-toggle')) {
                        var toggle = document.createElement('span');
                        toggle.className = 'rt-toggle';
                        toggle.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>';
                        firstCell.prepend(toggle);
                    }

                    tr.addEventListener('click', function (e) {
                        if (window.innerWidth > 879) return; // hanya aktif di mode kartu mobile
                        if (e.target.closest('a, button, form')) return; // jangan toggle saat klik tombol/link di dalam baris
                        tr.classList.toggle('rt-open');
                    });
                });
            });
        }
        document.addEventListener('DOMContentLoaded', tafMakeTablesResponsive);
    </script>

    {{-- Alpine dimuat sekali di sini untuk seluruh halaman admin (drawer & komponen) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('head')
</head>
<body>
@php
    $nav = [
        ['route'=>'admin.dashboard','pattern'=>'admin.dashboard','label'=>'Dashboard',
            'ico'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>'],
        ['route'=>'admin.pemesanan.index','pattern'=>'admin.pemesanan.*','label'=>'Pemesanan',
            'ico'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>'],
        ['route'=>'admin.jadwal.index','pattern'=>'admin.jadwal.*','label'=>'Jadwal Event',
            'ico'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/><circle cx="12" cy="14" r="1.4" fill="currentColor" stroke="none"/></svg>'],
        ['route'=>'admin.keuangan.index','pattern'=>'admin.keuangan.*','label'=>'Keuangan',
            'ico'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="6" width="19" height="13" rx="2.5"/><path d="M2.5 10h19"/><path d="M17 15h.01"/></svg>'],
        ['route'=>'admin.layanan.index','pattern'=>'admin.layanan.*','label'=>'Kelola Layanan',
            'ico'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="7" x2="20" y2="7"/><circle cx="9" cy="7" r="2.4"/><line x1="4" y1="17" x2="20" y2="17"/><circle cx="15" cy="17" r="2.4"/></svg>'],
        ['route'=>'admin.notifikasi.index','pattern'=>'admin.notifikasi.*','label'=>'Notifikasi WA',
            'ico'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>'],
        ['route'=>'admin.galeri.index','pattern'=>'admin.galeri.*','label'=>'Galeri',
            'ico'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2.5"/><circle cx="8.5" cy="9.5" r="1.6"/><path d="m4 17 4.5-4.5a2 2 0 0 1 2.8 0L20 21"/></svg>'],
    ];
@endphp
<div x-data="{ nav:false }">
    {{-- Top bar (mobile only) --}}
    <div class="topbar">
        <button class="hamburger" @click="nav=true" aria-label="Buka menu">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="logo">Taf <em>Wedding</em></a>
        <div style="width:42px;"></div>
    </div>

    {{-- Backdrop (mobile drawer) --}}
    <div class="backdrop" :class="{'show':nav}" @click="nav=false"></div>

    <div class="layout">
        {{-- Sidebar / Drawer --}}
        <aside class="side" :class="{'open':nav}">
            <div class="side-top">
                <div class="side-emblem"><img src="{{ asset('images/taf-emblem.png') }}" alt="Taf Wedding"></div>
                <div class="side-logo">Taf <em>Wedding</em></div>
            </div>
            <nav class="side-nav">
                @foreach ($nav as $n)
                    <a href="{{ route($n['route']) }}" class="nav-i {{ request()->routeIs($n['pattern']) ? 'on' : '' }}">
                        <span class="ico">{!! $n['ico'] !!}</span>{{ $n['label'] }}
                    </a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}" style="margin-top:22px;">
                    @csrf
                    <button type="submit" class="nav-i" style="width:100%;background:none;border:none;text-align:left;color:rgba(244,233,207,.5);">
                        <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h3"/><path d="m16 17 5-5-5-5M21 12H9"/></svg></span>Logout
                    </button>
                </form>
            </nav>
            <div class="side-foot">
                <div class="side-user">
                    <div class="av">{{ strtoupper(mb_substr(auth()->user()->name ?? 'TW', 0, 2)) }}</div>
                    <div>
                        <div class="side-name">{{ auth()->user()->name ?? 'Taf Wedding' }}</div>
                        <div class="side-role">Administrator</div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main content --}}
        <main class="main">
            <div class="main-inner">
                @yield('content')
            </div>
        </main>
    </div>
</div>
@stack('scripts')

@if (session('success') || session('error') || session('info') || session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success')) window.tafToast('success', @json(session('success'))); @endif
            @if (session('error'))   window.tafToast('error', @json(session('error'))); @endif
            @if (session('info'))    window.tafToast('info', @json(session('info'))); @endif
            @if (session('warning')) window.tafToast('warn', @json(session('warning'))); @endif
        });
    </script>
@endif

</body>
</html>