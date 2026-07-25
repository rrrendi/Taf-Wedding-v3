@extends('layouts.public')

@section('title', 'Taf Wedding — Wedding Organizer & Makeup Artist Bandung')

@push('head')
<style>
    /* KHUSUS TAMPILAN MOBILE: Mengubah Grid menjadi Slider/Geser Samping */
    @media (max-width: 767px) {
        .mobile-slider {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; 
            gap: 14px;
            margin-left: -20px;
            margin-right: -20px;
            padding-left: 20px;
            padding-right: 20px;
            padding-bottom: 15px;
            align-items: stretch !important; 
        }
        .mobile-slider::-webkit-scrollbar { 
            display: none; 
        }
        .mobile-slider > * {
            flex: 0 0 85% !important; 
            height: auto !important; 
        }
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    /* PERBAIKAN: Menghapus !important pada display agar fitur "Tutup Sebagian" Alpine.js berfungsi sempurna */
    .svc-card-flex {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
</style>
@endpush

@section('content')
@php
    // Data JSON untuk Modal Detail Paket
    $layananJson = $layanans->map(fn ($l) => [
        'id'         => $l->id,
        'nama'       => $l->nama,
        'harga'      => 'Rp ' . number_format((float) $l->harga, 0, ',', '.'),
        'deskripsi'  => $l->deskripsi,
        'gambar_url' => $l->gambar_url ?? null,
    ])->values();
@endphp

{{-- HEADER TETAP --}}
<header class="site-header" id="siteHeader">
    <div class="wrap">
        <a href="{{ route('landing') }}" class="logo">
            <img src="{{ asset('images/taf-emblem.png') }}" alt="Taf Wedding" class="logo-emblem">
            Taf <em>Wedding</em>
        </a>
        <div class="topnav-r">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-outline btn-sm">Dashboard</a>
                <a href="{{ route('client.pemesanan.create') }}" class="btn btn-gold btn-sm">Buat Pesanan</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-gold btn-sm">Daftar Sekarang</a>
            @endauth
        </div>
    </div>
</header>

<div class="fade">
    
    {{-- AREA HERO --}}
    <div class="hero">
        <div id="hero-bg-base" class="hero-bg-layer"></div>
        <div id="hero-bg-fade" class="hero-bg-layer"></div>

        <div id="hero-content" class="hero-center">
            <div class="hero-inner">
                <div class="hero-badge"><img src="{{ asset('images/taf-emblem.png') }}" alt="Taf Wedding"></div>
                <div class="hero-eyebrow">Wedding Organizer &amp; Makeup Artist · Bandung</div>
                <h1>Hari Bahagia Anda,<br><em>Dirangkai Tanpa Cela</em></h1>
                <div class="hero-line"></div>
                <p class="hero-desc">Tata rias memukau, dekorasi elegan, dan koordinasi yang rapi dalam satu tim terpercaya. Anda cukup menikmati momennya — sisanya kami yang urus.</p>
                <div class="hero-ctas">
                    <a href="{{ auth()->check() ? route('client.pemesanan.create') : route('register') }}" class="btn btn-gold">Mulai Buat Pesanan</a>
                    <a href="#cara-pesan" class="btn btn-outline">Cara Pemesanan</a>
                </div>
            </div>
        </div>
    </div>

    {{-- KONTEN CERAH BAWAH --}}
    <div id="content-wrapper" style="position: relative; z-index: 2; background-color: var(--bg);">
        
        {{-- CARA PEMESANAN --}}
        <div class="section" id="cara-pesan">
            <div class="sec-head">
                <div class="sec-eyebrow">Mudah &amp; Terstruktur</div>
                <div class="sec-title">Pesan dalam <em>3 Langkah</em></div>
                <div class="sec-desc">Tanpa chat bolak-balik yang membingungkan. Semua tercatat rapi dan bisa Anda pantau sendiri.</div>
            </div>
            <div class="steps-row mobile-slider">
                <div class="step-card"><div class="step-badge">1</div><h4>Daftar Akun</h4><p>Cukup nama, nomor WhatsApp, dan email. Gratis, dan hanya sekali untuk semua pemesanan Anda.</p></div>
                <div class="step-card"><div class="step-badge">2</div><h4>Pilih &amp; Jadwalkan</h4><p>Tentukan layanan, tanggal, dan lokasi acara. Tim kami langsung menerima detailnya secara lengkap.</p></div>
                <div class="step-card"><div class="step-badge">3</div><h4>Pantau Real-Time</h4><p>Lacak status, pembayaran, dan progres acara dari portal pribadi Anda — kapan saja, di mana saja.</p></div>
            </div>
            <div class="text-center" style="margin-top:24px;">
                <a href="{{ auth()->check() ? route('client.pemesanan.create') : route('register') }}" class="btn btn-dark">Daftar & Mulai Sekarang</a>
            </div>
        </div>

        {{-- LAYANAN KAMI --}}
        <div class="section" style="padding-top:0;" x-data="{ 
                openLayanan: false, 
                isMobile: window.innerWidth < 768,
                previewCount: window.innerWidth >= 1024 ? 4 : 3,
                layananData: {{ Illuminate\Support\Js::from($layananJson) }},
                isModalOpen: false,
                activeModalData: null,
                openInfoModal(id) {
                    this.activeModalData = this.layananData.find(l => l.id === id);
                    this.isModalOpen = true;
                    document.body.style.overflow = 'hidden'; 
                },
                closeInfoModal() {
                    this.isModalOpen = false;
                    document.body.style.overflow = ''; 
                    setTimeout(() => { this.activeModalData = null; }, 300); 
                }
            }" @resize.window="isMobile = window.innerWidth < 768; previewCount = window.innerWidth >= 1024 ? 4 : 3">
            
            <div class="sec-head">
                <div class="sec-eyebrow">Layanan Kami</div>
                <div class="sec-title">Semua Kebutuhan Pernikahan, <em>Satu Tim</em></div>
                <div class="sec-desc">Dari persiapan hingga hari-H, ditangani profesional yang berpengalaman menghadirkan ratusan momen bahagia.</div>
            </div>
            
            <div class="svc-grid mobile-slider">
                @foreach ($layanans as $i => $l)
                    <div class="svc-card svc-card-flex"
                         x-show="openLayanan || isMobile || {{ $i }} < previewCount"
                         x-transition.duration.300ms
                         style="{{ $i >= 4 ? 'display: none;' : 'animation:rise .4s '.($i * 0.05).'s ease both;' }}"
                         @click="openInfoModal({{ $l->id }})">

                        <div class="svc-photo" style="{{ $l->gambar_url ? 'background-image:url('.$l->gambar_url.')' : '' }}">
                            @unless ($l->gambar_url)
                                <div class="svc-photo-empty">
                                    <svg viewBox="0 0 24 24" width="34" height="34" fill="none" stroke="rgba(231,200,121,.3)" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                            @endunless
                            <div class="svc-photo-overlay"></div>
                            <button type="button" class="svc-info-btn" @click.stop="openInfoModal({{ $l->id }})" title="Detail layanan">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            </button>
                            <div class="svc-name-on-photo">{{ $l->nama }}</div>
                        </div>

                        <div class="svc-body">
                            <span class="svc-kategori-tag">{{ $l->kategori_label }}</span>
                            <div class="svc-desc">{{ $l->deskripsi ?: 'Belum ada deskripsi.' }}</div>
                            <div class="svc-foot">
                                <span class="svc-price">{{ $l->harga_format }}</span>
                                <span class="svc-add">Detail Paket</span>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
            
            <div class="text-center" x-show="!isMobile && {{ count($layanans) }} > previewCount" x-cloak style="margin-top: 20px;">
                <button type="button" @click="openLayanan = !openLayanan" style="background:transparent; border:none; color:var(--gold2); font-weight:700; font-size:13px; font-family:var(--sans); cursor:pointer; text-transform:uppercase; letter-spacing:1px; display:inline-flex; align-items:center; gap:6px;">
                    <span x-text="openLayanan ? 'Tutup Sebagian' : 'Lihat Selengkapnya (+' + ({{ count($layanans) }} - previewCount) + ')'"></span>
                    <svg x-show="!openLayanan" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    <svg x-show="openLayanan" x-cloak viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                </button>
            </div>

            {{-- CTA Khusus Paket --}}
            <div class="text-center" style="margin-top: 28px; border-top: 1px dashed var(--border); padding-top: 20px;">
                <p style="font-size:14px;color:var(--ink4);margin-bottom:12px;">Punya kebutuhan khusus? Kami siap merancang paket sesuai impian Anda.</p>
                <a href="{{ auth()->check() ? route('client.pemesanan.create') : route('register') }}" class="btn btn-gold">Cek &amp; Amankan Tanggal Anda</a>
            </div>

            {{-- MODAL INFO LAYANAN KHUSUS LANDING PAGE --}}
            <template x-teleport="body">
                <div x-show="isModalOpen" style="display: none;">
                    {{-- Latar Belakang Gelap --}}
                    <div x-show="isModalOpen" x-transition.opacity 
                         style="position: fixed; inset: 0; z-index: 999998; background: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px);" 
                         @click="closeInfoModal()"></div>
        
                    {{-- Kontainer Penengah Elemen --}}
                    <div style="position: fixed; inset: 0; z-index: 999999; display: flex; align-items: center; justify-content: center; pointer-events: none; padding: 20px;">
                        
                        {{-- Kotak Modal Putih --}}
                        <div x-show="isModalOpen" x-transition.opacity
                             @click.stop 
                             style="pointer-events: auto; background: #FFFFFF; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.5); width: 100%; max-width: 440px; overflow: hidden; display: flex; flex-direction: column; max-height: 90vh; position: relative;">
                            
                            {{-- Tombol Tutup --}}
                            <button type="button" @click="closeInfoModal()" style="position: absolute; top: 16px; right: 16px; background: rgba(0,0,0,0.5); border: none; width: 34px; height: 34px; border-radius: 50%; color: #FFF; cursor: pointer; display: flex; align-items: center; justify-content: center; z-index: 10; transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.5)'">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
        
                            {{-- Area Gambar Header --}}
                            <template x-if="activeModalData && activeModalData.gambar_url">
                                <div style="width: 100%; height: 240px; background: #f0f0f0;">
                                    <img :src="activeModalData.gambar_url" alt="Ilustrasi Paket" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </template>
                            <template x-if="activeModalData && !activeModalData.gambar_url">
                                <div style="width: 100%; height: 120px; background: linear-gradient(145deg, #1C1710, #110E08); display: flex; align-items: center; justify-content: center;">
                                    <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="rgba(231,200,121,0.2)" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                </div>
                            </template>
        
                            {{-- Konten Teks --}}
                            <div style="padding: 24px; overflow-y: auto;">
                                <h3 style="font-family: var(--serif); font-size: 24px; color: var(--ink); margin: 0 0 8px 0; line-height: 1.2;" x-text="activeModalData ? activeModalData.nama : ''"></h3>
                                <div style="color: var(--goldDeep); font-weight: 800; font-size: 16px; margin-bottom: 20px; font-family: var(--sans);" x-text="activeModalData ? activeModalData.harga : ''"></div>
                                <p style="color: var(--ink3); font-size: 14.5px; line-height: 1.6; margin: 0; white-space: pre-wrap;" x-text="activeModalData && activeModalData.deskripsi ? activeModalData.deskripsi : 'Belum ada deskripsi detail untuk layanan ini.'"></p>
                            </div>
                            
                            {{-- Footer Modal --}}
                            <div style="padding: 16px 24px; border-top: 1.5px solid var(--border); background: #FAFAFA; display: flex; justify-content: space-between; align-items: center;">
                                <a href="{{ auth()->check() ? route('client.pemesanan.create') : route('register') }}" style="color: var(--goldDeep); font-size: 14px; font-weight: 700; text-decoration: none;">+ Pesan Layanan Ini</a>
                                <button type="button" @click="closeInfoModal()" class="btn btn-outline" style="border-radius: 8px; font-weight: 600; font-size: 14px; height: 42px; padding: 0 24px; cursor: pointer;">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

        </div>
        {{-- Akhir dari Div Alpine.js "Layanan Kami" --}}

        {{-- GALERI --}}
        @if ($galeris->isNotEmpty())
        <div class="section" style="padding-top: 0;">
            <div class="sec-head">
                <div class="sec-eyebrow">Galeri</div>
                <div class="sec-title">Karya yang <em>Berbicara</em></div>
                <div class="sec-desc">Setiap pernikahan adalah cerita. Inilah beberapa momen indah yang pernah kami rangkai.</div>
            </div>
            
            <div x-data="{ openGaleri: false }">
                <div class="gallery-grid">
                    @foreach ($galeris as $i => $g)
                        <div class="gal-item" 
                             x-show="openGaleri || {{ $i }} < 6" 
                             x-transition.duration.300ms
                             style="cursor: pointer; {{ $i >= 6 ? 'display: none;' : '' }}"
                             @if($g->gambar) @click="$dispatch('open-lightbox', '{{ Storage::url($g->gambar) }}')" @endif>
                            
                            <div class="gal-inner" @if(! $g->gambar) style="background:linear-gradient(135deg,#2b2419,#5e4a24);" @endif>
                                @if ($g->gambar)<img src="{{ Storage::url($g->gambar) }}" alt="{{ $g->judul }}">@endif
                            </div>
                            <div class="gal-overlay"><span class="gal-label">{{ $g->judul }}</span></div>
                        </div>
                    @endforeach
                </div>

                @if(count($galeris) > 6)
                <div class="text-center" style="margin-top: 20px;">
                    <button type="button" @click="openGaleri = !openGaleri" style="background:transparent; border:none; color:var(--gold2); font-weight:700; font-size:13px; font-family:var(--sans); cursor:pointer; text-transform:uppercase; letter-spacing:1px; display:inline-flex; align-items:center; gap:6px;">
                        <span x-text="openGaleri ? 'Tutup Sebagian' : 'Lihat Selengkapnya (+{{ count($galeris) - 6 }})'"></span>
                        <svg x-show="!openGaleri" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        <svg x-show="openGaleri" x-cloak viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- TESTIMONI --}}
        <div class="section" style="padding-top:0;">
            <div class="sec-head">
                <div class="sec-eyebrow">Testimoni</div>
                <div class="sec-title">Dipercaya <em>Ratusan Pasangan</em></div>
                <div class="sec-desc">Kebahagiaan mereka adalah alasan kami terus memberikan yang terbaik.</div>
            </div>
            <div class="testi-grid mobile-slider">
                @php
                    $testi = [
                        ['name'=>'Risa &amp; Arief','text'=>'Dari dekorasi sampai makeup, semuanya melebihi ekspektasi. Hari pernikahan kami berjalan mulus tanpa satu pun kendala.'],
                        ['name'=>'Dina &amp; Fadhil','text'=>'Detailnya luar biasa diperhatikan. Tamu-tamu kami sampai bertanya siapa vendornya. Sangat direkomendasikan!'],
                        ['name'=>'Nanda &amp; Gilang','text'=>'Komunikasi rapi dari awal sampai akhir. Begitu lihat hasilnya, kami tahu memilih tim yang tepat.'],
                    ];
                @endphp
                @foreach ($testi as $i => $t)
                    <div class="testi-card" style="animation:rise .4s {{ $i * 0.08 }}s ease both;">
                        <div class="testi-quote">&ldquo;</div>
                        <div class="testi-stars">@for($s=0;$s<5;$s++)<span class="testi-star">&#9733;</span>@endfor</div>
                        <div class="testi-text">{!! $t['text'] !!}</div>
                        <div class="testi-author">— {!! $t['name'] !!}</div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- KEUNGGULAN --}}
        <div class="section" style="padding-top:0; padding-bottom: 20px;">
            <div class="sec-head">
                <div class="sec-eyebrow">Mengapa Taf Wedding</div>
                <div class="sec-title">Bukan Sekadar Vendor, <em>Tapi Ketenangan</em></div>
                <div class="sec-desc">Sistem yang rapi dan transparan agar Anda fokus berbahagia, bukan sibuk mengurus detail.</div>
            </div>
            <div class="why-grid mobile-slider">
                @php
                    $why = [
                        ['t'=>'Satu Pintu, Tanpa Ribet','d'=>'Cukup isi satu form. Data lengkap tercatat rapi — tanpa chat bolak-balik dan tanpa yang terlewat.',
                            'ic'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="m9 14 2 2 4-4"/></svg>'],
                        ['t'=>'Tanggal Anda Aman','d'=>'Sistem jadwal terpusat dengan deteksi bentrok otomatis. Tanggal Anda dikunci begitu dikonfirmasi.',
                            'ic'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/><path d="m9 15 2 2 4-4"/></svg>'],
                        ['t'=>'Pembayaran Transparan','d'=>'Pantau DP, pelunasan, dan sisa tagihan real-time. Invoice resmi tersedia kapan saja.',
                            'ic'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="6" width="19" height="13" rx="2.5"/><path d="M2.5 10h19M17 15h.01"/></svg>'],
                        ['t'=>'Selalu Diingatkan','d'=>'Konfirmasi, pengingat bayar, dan reminder jelang hari-H dikirim otomatis ke WhatsApp Anda.',
                            'ic'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.3 7.5L3 21l2-5.7A8.4 8.4 0 1 1 21 11.5Z"/></svg>'],
                    ];
                @endphp
                @foreach ($why as $w)
                    <div class="why-card">
                        <div class="why-icon">{!! $w['ic'] !!}</div>
                        <div><div class="why-title">{{ $w['t'] }}</div><div class="why-desc">{!! $w['d'] !!}</div></div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- CTA BANNER BAWAH --}}
        <div style="padding:0 20px 24px; max-width:var(--maxw); margin:0 auto;">
            <div class="cta-banner">
                <h2>Tanggal Terbaik Cepat Penuh.<br><em>Amankan Milik Anda.</em></h2>
                <p>Mulai amankan jadwal acara dan wujudkan konsep impian Anda bersama kami.</p>
                <a href="{{ auth()->check() ? route('client.pemesanan.create') : route('register') }}" class="btn btn-gold">Buat Pesanan Sekarang</a>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <div class="logo">Taf <em>Wedding</em></div>
            <div class="footer-contact">
                Taman Holis Indah Belakang Blok C1 No.6, Cigondewah Rahayu, Kota Bandung<br>
                WhatsApp: 0857-2138-2933 &nbsp;·&nbsp; 0857-9436-6898
            </div>
            <div class="footer-line"></div>
            <p>© {{ date('Y') }} Taf Wedding Bandung — Wedding Organizer &amp; Makeup Artist by Waode Trismawati</p>
        </div>

    </div>
</div>

{{-- GLOBAL LIGHTBOX MODAL (UNTUK FOTO GALERI) --}}
<div x-data="{ lightboxOpen: false, lightboxImg: '' }" 
     @open-lightbox.window="lightboxImg = $event.detail; lightboxOpen = true"
     x-show="lightboxOpen" 
     x-cloak 
     x-transition.opacity.duration.300ms 
     style="position: fixed; inset: 0; z-index: 99999; background: rgba(10,8,5,0.95); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; padding: 20px; cursor: pointer;"
     x-effect="document.body.style.overflow = lightboxOpen ? 'hidden' : ''"
     @click="lightboxOpen = false"> 
    
    <button @click="lightboxOpen = false" style="position: absolute; top: 20px; right: 25px; background: transparent; border: 1px solid rgba(231,200,121,0.3); border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; color: #EFE2C4; cursor: pointer; z-index: 100000; transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>

    <div style="display: flex; justify-content: center; align-items: center; width: 100%; height: 100%; -webkit-tap-highlight-color: transparent;">
        <img :src="lightboxImg" 
             @click.stop 
             style="max-width: 100%; max-height: 85vh; object-fit: contain; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.5); cursor: default;">
    </div>
</div>

@endsection

@push('scripts')
<script>
    (function () {
        var h = document.getElementById('siteHeader');
        if (h) {
            var toggle = function () { h.classList.toggle('scrolled', window.scrollY > 40); };
            toggle();
            window.addEventListener('scroll', toggle, { passive: true });
        }

        var heroBgFade = document.getElementById('hero-bg-fade');
        if (heroBgFade) {
            var handleFade = function() {
                if (window.scrollY > 15) {
                    heroBgFade.classList.add('scrolled-out');
                } else {
                    heroBgFade.classList.remove('scrolled-out');
                }
            };
            handleFade(); 
            window.addEventListener('scroll', handleFade, { passive: true });
        }

        if (window.innerWidth < 768) {
            var sliders = document.querySelectorAll('.mobile-slider');
            sliders.forEach(function(slider) {
                var isTouched = false;
                var direction = 1;
                var speed = 0.5; 
                
                function autoScroll() {
                    if (!isTouched && window.innerWidth < 768) {
                        slider.scrollLeft += (speed * direction);
                        
                        if (slider.scrollLeft >= (slider.scrollWidth - slider.clientWidth - 2)) {
                            direction = -1;
                        } 
                        else if (slider.scrollLeft <= 0) {
                            direction = 1;
                        }
                    }
                    requestAnimationFrame(autoScroll);
                }
                
                setTimeout(function() {
                    requestAnimationFrame(autoScroll);
                }, 2000);
                
                slider.addEventListener('touchstart', function() { isTouched = true; }, {passive: true});
                slider.addEventListener('touchend', function() { 
                    setTimeout(function(){ isTouched = false; }, 0); 
                }, {passive: true});
            });
        }
    })();
</script>
@endpush