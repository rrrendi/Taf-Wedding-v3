@extends('layouts.public')

@section('title', $pemesanan->kode . ' — Detail Pesanan')

@push('head')
    <style>
        /* DESAIN TABEL PEMBAYARAN KHUSUS (Mencegah Card Menumpuk Card) */
        .pay-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pay-table th,
        .pay-table td {
            text-align: left !important;
            padding: 16px 20px !important;
        }

        .pay-table th {
            border-bottom: 1.5px solid var(--border);
            color: var(--ink3);
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            background: rgba(0, 0, 0, 0.01);
            white-space: nowrap !important;
        }

        .pay-table td {
            border-bottom: 1px dashed var(--border2);
            vertical-align: middle;
            font-size: 14px;
            color: var(--ink);
            white-space: nowrap !important;
        }

        .pay-table td .badge,
        .pay-table td .pill-link {
            margin: 0 !important;
            display: inline-block !important;
            white-space: nowrap !important;
        }

        .pay-col {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .pay-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* TAMPILAN MOBILE: Berubah menjadi List Transaksi Elegan */
        @media (max-width: 767px) {
            .pay-table thead {
                display: none;
            }

            .pay-table tbody {
                display: flex;
                flex-direction: column;
                gap: 10px;
                padding: 14px;
            }

            .pay-table tr {
                display: grid;
                grid-template-columns: 1fr auto;
                gap: 6px 12px;
                background: #FFFFFF;
                border: 1.5px solid var(--border2);
                border-radius: 12px;
                padding: 12px 14px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            }

            .pay-table td {
                padding: 0 !important;
                border-bottom: none !important;
                white-space: normal !important;
                display: block;
            }

            .pay-table td::before {
                content: none;
            }

            /* Susun ulang tampilan tanpa mengubah urutan kolom di HTML */
            .pay-table td:nth-child(1) {
                grid-column: 1;
                grid-row: 1;
            }

            /* Waktu Bayar */
            .pay-table td:nth-child(4) {
                grid-column: 2;
                grid-row: 1;
                justify-self: end;
            }

            /* Status */
            .pay-table td:nth-child(2) {
                grid-column: 1;
                grid-row: 2;
                font-size: 12.5px;
                color: var(--muted);
            }

            /* Jenis */
            .pay-table td:nth-child(3) {
                grid-column: 2;
                grid-row: 2;
                justify-self: end;
            }

            /* Jumlah */
            .pay-table td:nth-child(5) {
                grid-column: 1 / -1;
                grid-row: 3;
                margin-top: 6px;
                padding-top: 8px !important;
                border-top: 1px dashed rgba(0, 0, 0, 0.06) !important;
            }

            /* Bukti, full-width, dipisah garis tipis */

            .pay-table td:nth-child(1) .pay-col {
                align-items: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $sb = $pemesanan->status_bayar; // belum | dp | lunas
        $st = $pemesanan->status;       // pending | dikonfirmasi | selesai | dibatalkan
        $isPast = $pemesanan->tanggal_acara->isPast();

        $stepKonfirmasi = in_array($st, ['dikonfirmasi', 'selesai']) ? 'ok' : ($st === 'dibatalkan' ? 'wait' : 'now');
        $stepDp = $pemesanan->terbayar > 0 ? 'ok' : ($st === 'dikonfirmasi' ? 'now' : 'wait');
        $stepPelunasan = $sb === 'lunas' ? 'ok' : ($sb === 'dp' ? 'now' : 'wait');
        $stepPersiapan = $st === 'selesai' ? 'ok' : (($sb === 'lunas' && $st === 'dikonfirmasi') ? 'now' : 'wait');
        $stepHariH = ($st === 'selesai' || $isPast) ? 'ok' : 'wait';

        $timeline = [
            ['st' => 'ok', 't' => 'Pemesanan Diterima', 's' => 'Data pemesanan tercatat di sistem'],
            ['st' => $stepKonfirmasi, 't' => 'Pemesanan Dikonfirmasi', 's' => $stepKonfirmasi === 'ok' ? 'Admin telah memverifikasi data Anda' : 'Menunggu verifikasi admin'],
            ['st' => $stepDp, 't' => 'Pembayaran DP', 's' => $pemesanan->terbayar > 0 ? ('Pembayaran ' . $pemesanan->terbayar_format . ' tercatat') : 'Menunggu pembayaran DP'],
            ['st' => $stepPelunasan, 't' => 'Pelunasan', 's' => $sb === 'lunas' ? 'Pembayaran lunas' : ('Sisa ' . $pemesanan->sisa_format)],
            ['st' => $stepPersiapan, 't' => 'Persiapan Hari-H', 's' => 'Koordinasi seluruh layanan dan vendor'],
            ['st' => $stepHariH, 't' => 'Hari Pernikahan', 's' => $pemesanan->tanggal_acara->translatedFormat('l, d F Y')],
        ];

        $tlIcon = function ($st) {
            if ($st === 'ok')
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>';
            if ($st === 'now')
                return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>';
            return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="12" cy="12" r="5"/></svg>';
        };
    @endphp

@include('client.partials.topbar', ['current' => $pemesanan->kode])
    <div class="cl-page fade" style="padding-top: 24px;">

        <div
            style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin:0 0 24px;">
            <div>
                <h1 style="font-family:var(--serif);font-size:clamp(24px,4vw,28px);color:var(--ink);">Detail Pesanan</h1>
                <p style="font-size:13.5px;color:var(--muted);margin-top:6px;">Pantau status pemesanan dan riwayat
                    pembayaran untuk ID Berkas: <strong style="color:var(--ink);">{{ $pemesanan->kode }}</strong></p>
            </div>
            <a href="{{ route('client.pemesanan.index') }}"
                style="font-size:13px;font-weight:700;color:var(--ink3);text-decoration:none;display:flex;align-items:center;gap:6px;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Kembali ke Portal
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom: 24px;"><span>{{ $errors->first() }}</span></div>
        @endif

        {{-- Tata Letak Grid Asimetris 2 Kolom --}}
        <div class="grid2">

            {{-- KOLOM KIRI --}}
            <div style="display: flex; flex-direction: column; gap: 20px;">

                {{-- ORDER CARD UTAMA GELAP --}}
                <div class="order-card" style="margin-bottom: 0;">
                    <div class="o-id">{{ $pemesanan->kode }}</div>
                    <div class="o-name">{{ $pemesanan->nama_klien }}</div>
                    <div class="o-date">{{ $pemesanan->tanggal_acara->translatedFormat('l, d F Y') }} ·
                        {{ $pemesanan->lokasi }}</div>

                    {{-- 1. Layanan yang Dipesan --}}
                    <div style="margin-top:20px; padding-top:18px; border-top:1px solid rgba(231,200,121,.2);">
                        <div
                            style="font-size:11px; text-transform:uppercase; letter-spacing:.5px; color:#A99B82; margin-bottom:10px;">
                            Layanan yang Dipesan</div>
                        <div style="display:flex; flex-wrap:wrap; gap:8px;">
                            @foreach ($pemesanan->layanans as $l)
                                <span
                                    style="background:rgba(231,200,121,.15); color:var(--gold3); border:1px solid rgba(231,200,121,.3); padding:8px 14px; border-radius:999px; font-size:12px; font-weight:700;">
                                    {{ $l->nama }} · {{ 'Rp ' . number_format((float) $l->pivot->subtotal, 0, ',', '.') }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. Data Keuangan --}}
                    <div class="o-meta">
                        <div class="o-m">
                            <div class="o-ml">Status Pesanan</div>
                            <div class="o-mv"
                                style="color:{{ $st === 'dikonfirmasi' || $st === 'selesai' ? '#7BCBA0' : ($st === 'dibatalkan' ? '#E0A0A0' : 'var(--gold3)') }};">
                                {{ $pemesanan->status_label }}</div>
                        </div>
                        <div class="o-m">
                            <div class="o-ml">Total Biaya</div>
                            <div class="o-mv">{{ $pemesanan->total_format }}</div>
                        </div>
                        <div class="o-m">
                            <div class="o-ml">Sisa Pembayaran</div>
                            <div class="o-mv" style="color:var(--gold3);">{{ $pemesanan->sisa_format }}</div>
                        </div>
                    </div>
                    <div style="margin-top:18px;padding-top:18px;border-top:1px solid rgba(231,200,121,.2);">
                        @php $persen = $pemesanan->total > 0 ? min(100, ($pemesanan->terbayar / $pemesanan->total) * 100) : 0; @endphp
                        <div style="height:8px;background:rgba(255,255,255,.08);border-radius:99px;overflow:hidden;">
                            <div style="height:100%;width:{{ $persen }}%;background:var(--gold-grad);border-radius:99px;transition:width .4s ease;"></div>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-top:8px;font-size:12.5px;">
                            <span style="color:#7BCBA0;font-weight:600;">Terbayar Rp {{ number_format($pemesanan->terbayar, 0, ',', '.') }}</span>
                            <span style="color:var(--gold3);font-weight:600;">Sisa {{ $pemesanan->sisa_format }}</span>
                        </div>
                    </div>

                    {{-- 3. Aksi Pesanan (Batal/Hapus) --}}
                    @php
                        $bisaBatal = in_array($st, ['pending', 'dikonfirmasi']);
                        $bisaHapus = $pemesanan->pembayarans->count() === 0 && in_array($st, ['pending', 'dibatalkan']);
                    @endphp
                    @if ($bisaBatal || $bisaHapus)
                        <div
                            style="margin-top:20px; padding-top:18px; border-top:1px solid rgba(231,200,121,.2); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
                            <div style="font-size:12px; color:#A99B82; line-height:1.5; max-width:65%;">
                                @if ($pemesanan->terbayar > 0)
                                    Pembatalan tidak menghapus data. DP yang dibayarkan tidak dapat dikembalikan.
                                @else
                                    Anda dapat membatalkan atau menghapus pesanan ini selama belum ada pembayaran.
                                @endif
                            </div>
                            <div style="display:flex; gap:8px;">
                                @if ($bisaBatal)
                                    <form method="POST" action="{{ route('client.pemesanan.batal', $pemesanan) }}"
                                        onsubmit="return confirm('Batalkan pesanan ini?{{ $pemesanan->terbayar > 0 ? ' DP yang sudah dibayar tidak dapat dikembalikan.' : '' }}')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm"
                                            style="background:transparent; color:#E7C879; border:1.5px solid rgba(231,200,121,0.35); padding:8px 14px;">Batalkan
                                            Pesanan</button>
                                    </form>
                                @endif
                                @if ($bisaHapus)
                                    <form method="POST" action="{{ route('client.pemesanan.destroy', $pemesanan) }}"
                                        onsubmit="return confirm('Hapus pesanan ini secara permanen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm"
                                            style="background:rgba(227,161,161,.15); color:#E3A1A1; border:1.5px solid transparent; padding:8px 14px;">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Riwayat pembayaran --}}
                <div class="card" style="margin-bottom: 0; overflow: hidden;">
                    <div class="card-h"><span class="card-t">Riwayat Pembayaran</span></div>

                    @if ($pemesanan->pembayarans->isEmpty())
                        <div
                            style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; text-align: center; color: var(--muted);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round"
                                style="width: 48px; height: 48px; margin-bottom: 12px; color: var(--border2);">
                                <rect x="3" y="4" width="18" height="16" rx="2" />
                                <path d="M3 10h18M8 14h.01M12 14h.01M16 14h.01" />
                            </svg>
                            <span style="font-size: 14px; font-weight: 500;">Belum ada riwayat pembayaran untuk pesanan
                                ini.</span>
                        </div>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="pay-table">
                                <thead>
                                    <tr>
                                        <th>Waktu Bayar</th>
                                        <th>Jenis</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pemesanan->pembayarans as $bayar)
                                        <tr>
                                            <td data-label="Waktu Bayar">
                                                <div class="pay-col">
                                                    <span
                                                        style="font-weight: 600;">{{ $bayar->tanggal_bayar->translatedFormat('d M Y') }}</span>
                                                    <span
                                                        style="font-size:11.5px; color:var(--muted); margin-top: 2px;">{{ $bayar->tanggal_bayar->translatedFormat('H:i:s') }}
                                                        WIB</span>
                                                </div>
                                            </td>
                                            <td data-label="Jenis Pembayaran">
                                                <span style="font-weight: 600;">{{ $bayar->jenis_label }}</span>
                                            </td>
                                            <td data-label="Jumlah Tagihan">
                                                <strong
                                                    style="color: var(--ink); font-size: 15px;">{{ 'Rp ' . number_format((float) $bayar->jumlah, 0, ',', '.') }}</strong>
                                            </td>
                                            <td data-label="Status Berkas">
                                                <span
                                                    class="badge {{ $bayar->status === 'terverifikasi' ? 'b-green' : ($bayar->status === 'ditolak' ? 'b-red' : 'b-orange') }}">{{ $bayar->status_label }}</span>
                                            </td>
                                            <td data-label="File Bukti">
                                                @if ($bayar->bukti)
                                                    <a class="pill-link" href="{{ Storage::url($bayar->bukti) }}" target="_blank"
                                                        style="padding: 6px 14px; font-size: 12px;">Lihat Bukti</a>
                                                @else
                                                    <span style="color: var(--muted); font-size: 12px;">Tidak ada file</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div style="display: flex; flex-direction: column; gap: 20px;">

                {{-- Upload Bukti Pembayaran Premium --}}
                @if ($st !== 'dibatalkan' && $sb !== 'lunas')
                    <div class="card" x-data="{ 
                                name: '', 
                                jenis: '', 
                                jumlahRaw: '', 
                                jumlahFormatted: '',
                                sisa: {{ $pemesanan->sisa }},
                                total: {{ $pemesanan->total }},

                                autoFillJumlah() {
                                    let nominal = 0;
                                    if (this.jenis === 'pelunasan') {
                                        // Pelunasan selalu pas sesuai sisa tagihan
                                        nominal = this.sisa;
                                    } else if (this.jenis === 'dp') {
                                        // DP otomatis 10% dari total (boleh diedit manual jika ingin bayar lebih)
                                        nominal = Math.ceil(this.total * 0.10);
                                    } else {
                                        // Cicilan: nominal diketik manual oleh klien
                                        nominal = 0;
                                    }
                                    this.jumlahRaw = nominal ? String(nominal) : '';
                                    this.jumlahFormatted = nominal ? new Intl.NumberFormat('id-ID').format(nominal) : '';
                                },

                                get isOverpayment() {
                                    return Number(this.jumlahRaw) > this.sisa;
                                },
                                get errorPelunasan() {
                                    return this.jenis === 'pelunasan' && Number(this.jumlahRaw) !== this.sisa;
                                },
                                get errorDp() {
                                    return this.jenis === 'dp' && Number(this.jumlahRaw) > 0 && Number(this.jumlahRaw) < {{ $pemesanan->total * 0.10 }};
                                },
                                get isInvalid() {
                                    return this.isOverpayment || this.errorPelunasan || this.errorDp || Number(this.jumlahRaw) <= 0;
                                },
                                formatRupiah(e) {
                                    let angka = e.target.value.replace(/[^0-9]/g, '');
                                    this.jumlahRaw = angka;
                                    this.jumlahFormatted = angka ? new Intl.NumberFormat('id-ID').format(angka) : '';
                                }
                             }"
                        style="background: radial-gradient(70% 90% at 100% 0%, rgba(201, 162, 75, 0.12), transparent 65%), linear-gradient(155deg, #1C1710, #110E08); border: 1.5px solid var(--border2); color: #F4E8CC; margin-bottom: 0;">
                        <div class="card-h"
                            style="background: rgba(255, 255, 255, 0.03); border-bottom: 1.5px solid rgba(231, 200, 121, 0.2); padding: 14px 20px;">
                            <span class="card-t" style="color: #FCF7EB; font-size: 15px;">Upload Bukti Pembayaran</span>
                        </div>
                        <div style="padding:20px;">

                            <div class="alert"
                                style="background: rgba(46, 94, 150, 0.15); border-color: rgba(46, 94, 150, 0.4); color: #92BBE6; font-size:12px; margin-bottom: 16px; line-height: 1.5;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" style="width:16px; height:16px; margin-top:2px; flex:none;">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 11v5M12 8h.01" />
                                </svg>
                                <span>Transfer ke <strong>BCA 3790970682</strong> / <strong>SeaBank 901322162960</strong> a.n.
                                    Waode Trismawati.</span>
                            </div>

                            <form method="POST" action="{{ route('client.pembayaran.store', $pemesanan) }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div style="display: flex; flex-direction: column; gap: 12px;">
                                    <div class="field" style="margin-bottom: 0;">
                                        <label style="color: var(--gold3); font-weight: 700; font-size: 11px;">Jenis
                                            Pembayaran</label>
                                        <select class="input" name="jenis" x-model="jenis" @change="autoFillJumlah()" required
                                            style="background: #FFFFFF; color: var(--ink); border: 1.5px solid var(--border2); height: 40px; padding: 0 12px; border-radius: var(--r3);">
                                            <option value="" disabled selected>Pilih Jenis...</option>
                                            {{-- Menyembunyikan opsi DP jika sudah pernah bayar --}}
                                            @if ($pemesanan->terbayar == 0)
                                                <option value="dp">DP (Down Payment)</option>
                                            @endif
                                            <option value="pelunasan">Pelunasan</option>
                                            {{-- Cicilan hanya muncul setelah DP pertama sudah dibayar --}}
                                            @if ($pemesanan->terbayar > 0)
                                                <option value="cicilan">Cicilan</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="field" style="margin-bottom: 0;">
                                        <label style="color: var(--gold3); font-weight: 700; font-size: 11px;">Jumlah
                                            (Rp)</label>

                                        {{-- HIDDEN INPUT: Ini yang akan dibaca oleh Database (Tanpa Titik) --}}
                                        <input type="hidden" name="jumlah" :value="jumlahRaw">

                                        {{-- VISIBLE INPUT: Ini yang dilihat oleh klien (Bisa diketik & otomatis bertitik) --}}
                                        <input class="input" type="text" inputmode="numeric" :value="jumlahFormatted"
                                            @input="formatRupiah" placeholder="Maks: {{ $pemesanan->sisa_format }}" required
                                            style="background: #FFFFFF; color: var(--ink); border: 1.5px solid var(--border2); height: 40px; padding: 0 12px; border-radius: var(--r3);">

                                        {{-- Pesan Peringatan Overpayment --}}
                                        <div x-show="isOverpayment" x-cloak
                                            style="color: #FF8F8F; font-size: 11.5px; margin-top: 6px; font-weight: 600; display: flex; align-items: flex-start; gap: 6px; line-height: 1.3;">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" style="flex:none; margin-top: 1px;">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="12" y1="8" x2="12" y2="12" />
                                                <line x1="12" y1="16" x2="12.01" y2="16" />
                                            </svg>
                                            <span>Jumlah pembayaran tidak boleh melebihi sisa tagihan Anda
                                                ({{ $pemesanan->sisa_format }}).</span>
                                        </div>

                                        {{-- Pesan Panduan Khusus Pelunasan --}}
                                        <div x-show="errorPelunasan && !isOverpayment" x-cloak
                                            style="color: #FF8F8F; font-size: 11.5px; margin-top: 6px; font-weight: 600; display: flex; align-items: flex-start; gap: 6px; line-height: 1.3;">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" style="flex:none; margin-top: 1px;">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="12" y1="8" x2="12" y2="12" />
                                                <line x1="12" y1="16" x2="12.01" y2="16" />
                                            </svg>
                                            <span>Khusus Pelunasan, nominal harus pas sesuai sisa tagihan
                                                ({{ $pemesanan->sisa_format }}).</span>
                                        </div>

                                        {{-- Pesan Peringatan Minimal DP 10% --}}
                                        <div x-show="errorDp && !isOverpayment" x-cloak
                                            style="color: #FF8F8F; font-size: 11.5px; margin-top: 6px; font-weight: 600; display: flex; align-items: flex-start; gap: 6px; line-height: 1.3;">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" style="flex:none; margin-top: 1px;">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="12" y1="8" x2="12" y2="12" />
                                                <line x1="12" y1="16" x2="12.01" y2="16" />
                                            </svg>
                                            <span>Minimal pembayaran DP adalah 10% (Rp
                                                {{ number_format($pemesanan->total * 0.10, 0, ',', '.') }}).</span>
                                        </div>
                                    </div>

                                    <div class="field" style="margin-bottom: 0;">
                                        <label style="color: var(--gold3); font-weight: 700; font-size: 11px;">Metode</label>
                                        <input class="input" name="metode" placeholder="cth: Transfer BCA"
                                            style="background: #FFFFFF; color: var(--ink); border: 1.5px solid var(--border2); height: 40px; padding: 0 12px; border-radius: var(--r3);">
                                    </div>

                                    <div class="field" style="margin-bottom: 0; margin-top: 4px;">
                                        <label style="color: var(--gold3); font-weight: 700; font-size: 11px;">File Bukti (JPG,
                                            PNG, PDF — maks 5MB)</label>
                                        <label class="upload"
                                            style="display:block; background: rgba(255, 255, 255, 0.04); border: 1.5px dashed rgba(231, 200, 121, 0.4); border-radius: var(--r3); padding: 16px 12px; text-align: center; cursor: pointer;"
                                            @click.prevent="$refs.bukti.click()">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="rgba(231, 200, 121, 0.8)"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                style="width: 24px; height: 24px; margin: 0 auto 6px;">
                                                <path d="M12 16V4M7 9l5-5 5 5" />
                                                <path d="M4 16v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" />
                                            </svg>
                                            <div style="font-size:12px; font-weight:600; color:#FCF7EB;"
                                                x-text="name ? name : 'Pilih file bukti (ketuk)'"></div>
                                        </label>
                                        <input type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf" x-ref="bukti"
                                            @change="name = $refs.bukti.files[0].name" style="display:none;" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-gold btn-full" :disabled="isInvalid"
                                    :style="isInvalid ? 'opacity: 0.4; cursor: not-allowed;' : ''"
                                    style="margin-top: 16px; height: 42px; font-weight: 700; transition: all 0.2s;">Kirim Bukti
                                    Pembayaran</button>
                            </form>
                        </div>
                    </div>
                @elseif ($sb === 'lunas')
                    <div class="card" style="margin-bottom: 0;">
                        <div class="card-b" style="text-align:center;padding:30px 20px;">
                            <div
                                style="width:52px;height:52px;border-radius:50%;background:var(--greenBg);color:var(--green);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                            </div>
                            <div style="font-weight:700;font-size:15px;color:var(--ink);">Pembayaran Lunas</div>
                            <div style="color:var(--muted);font-size:12.5px;margin-top:6px;line-height:1.5;">
                                Seluruh tagihan untuk pesanan ini sudah terbayar penuh. Terima kasih! Rincian pembayaran ada di
                                tabel Riwayat Pembayaran.
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TIMELINE PROGRESS (Mode Bersih Putih) --}}
                <div
                    style="background: #FFFFFF; border: 1.5px solid var(--border); border-radius: var(--r2); padding: 26px 24px; box-shadow: var(--sh1); margin-bottom: 0;">
                    <div style="border-left: 4px solid var(--gold); padding-left: 14px; margin-bottom: 28px;">
                        <div
                            style="font-family: var(--serif); font-size: 22px; font-weight: 600; color: var(--ink); line-height: 1.1; letter-spacing: -0.2px;">
                            Timeline Progress</div>
                        <div style="font-size: 13px; color: var(--muted); margin-top: 4px; font-weight: 500;">Riwayat
                            internal pelacakan status resmi</div>
                    </div>
                    <div class="tl" style="padding-left: 4px;">
                        @foreach ($timeline as $x)
                            <div class="tl-i" style="padding-bottom: 24px;">
                                <div class="tl-dot tl-{{ $x['st'] }}" style="box-shadow: 0 0 0 3px #FFFFFF;">
                                    {!! $tlIcon($x['st']) !!}</div>
                                <div style="padding-top: 2px;">
                                    <div class="tl-t" style="font-size: 14.5px; font-weight: 700; color: var(--ink);">
                                        {{ $x['t'] }}</div>
                                    <div class="tl-s"
                                        style="font-size: 12.5px; color: var(--ink3); margin-top: 3px; line-height: 1.4;">
                                        {{ $x['s'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection