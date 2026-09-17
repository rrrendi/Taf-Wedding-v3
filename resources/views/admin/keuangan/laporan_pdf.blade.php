<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
@php
    // Kop surat: embed sebagai data-URI (sama seperti di invoice) supaya logo selalu tampil di dompdf.
    $logoFile = public_path('images/taf-invoice-logo.jpg');
    $wtFile   = public_path('images/wt-mark.png');
    $logoSrc  = is_file($logoFile) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoFile)) : null;
    $wtSrc    = is_file($wtFile)   ? 'data:image/png;base64,'  . base64_encode(file_get_contents($wtFile))   : null;

    $rupiah = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp
<style>
    @page { margin: 32px 38px; }
    * { box-sizing: border-box; }
    body { font-family: 'Helvetica', sans-serif; color: #1b1b1b; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; }
    td { vertical-align: top; }

    /* ===== KOP SURAT ===== */
    .kop { border-bottom: 2px solid #161616; padding-bottom: 10px; margin-bottom: 4px; }
    .kop-logo { width: 92px; height: auto; border-radius: 4px; }
    .kop-logo-fallback {
        width: 92px; height: 68px; background: #0d0d0d; color: #d9bc8e;
        text-align: center; border-radius: 4px; padding-top: 12px;
    }
    .kop-logo-fb-txt { font-family: 'Times', serif; font-size: 19px; font-weight: bold; letter-spacing: 2px; }
    .kop-logo-fb-sub { font-size: 6.5px; letter-spacing: 1.5px; margin-top: 3px; }
    .kop-brand { font-family: 'Times', serif; font-size: 21px; font-weight: bold; letter-spacing: 1px; color: #161616; }
    .kop-brand em { color: #C0596A; font-style: italic; }
    .kop-role { font-size: 9.5px; color: #555; margin-top: 2px; }
    .kop-addr { font-size: 8px; color: #777; letter-spacing: .3px; margin-top: 5px; line-height: 1.45; }
    .kop-mark-wrap { text-align: right; }
    .kop-mark { width: 54px; height: auto; }

    .title-wrap { text-align: center; margin: 12px 0 18px; }
    .title { font-family: 'Times', serif; font-size: 15px; font-weight: bold; letter-spacing: 1.5px; color: #161616; }
    .period { font-size: 10.5px; color: #444; margin-top: 3px; }

    /* ===== KONTEN LAPORAN (tidak berubah) ===== */
    .cards { width: 100%; margin: 6px 0 18px; }
    .cards td { width: 33%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; }
    .c-lbl { font-size: 9px; text-transform: uppercase; letter-spacing: 1px; color: #888; }
    .c-val { font-size: 16px; font-weight: bold; font-family: 'Times', serif; margin-top: 4px; }

    table.data { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
    table.data th { text-align: left; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.5px;
        color: #555; border-bottom: 1.5px solid #161616; padding: 7px 6px; }
    table.data td { padding: 6px; font-size: 10.5px; border-bottom: 1px solid #eee; }
    .amt { text-align: right; }
    .sec-title { font-family: 'Times', serif; font-size: 14px; margin: 6px 0 8px; }
    .foot { margin-top: 24px; font-size: 9px; color: #888; text-align: center; border-top: 1px solid #ddd; padding-top: 8px; }
    .green { color: #3D7A5A; font-weight: bold; }
    .red { color: #9B4040; font-weight: bold; }
</style>
</head>
<body>

{{-- ════════ KOP TAF WEDDING ════════ --}}
<table class="kop">
    <tr>
        <td style="width:100px;">
            @if ($logoSrc)
                <img class="kop-logo" src="{{ $logoSrc }}" alt="Taf Wedding">
            @else
                <div class="kop-logo-fallback">
                    <div class="kop-logo-fb-txt">TAF</div>
                    <div class="kop-logo-fb-sub">TAF WEDDING BY WAODE</div>
                </div>
            @endif
        </td>
        <td style="padding-left:12px;">
            <div class="kop-brand">TAF <em>WEDDING</em></div>
            <div class="kop-role">Waode Trismawati &mdash; Wedding Organizer &amp; Makeup Artist</div>
            <div class="kop-addr">TAMAN HOLIS INDAH BELAKANG BLOK C1.NO.6 KP. MAHKELUNG CIGONDEWAH RAHAYU KOTA BANDUNG</div>
            <div class="kop-addr">GALLERY : THE GPA LUXURY CLUSTER ARRAYA BLOK E-20 BALEENDAH</div>
        </td>
        @if ($wtSrc)
            <td style="width:80px;" class="kop-mark-wrap">
                <img class="kop-mark" src="{{ $wtSrc }}" alt="Taf Wedding">
            </td>
        @endif
    </tr>
</table>

<div class="title-wrap">
    <div class="title">LAPORAN KEUANGAN</div>
    <div class="period">Periode: {{ $awal->translatedFormat('F Y') }}</div>
</div>

{{-- ════════ RINGKASAN ════════ --}}
<table class="cards">
    <tr>
        <td>
            <div class="c-lbl">Pemasukan Terverifikasi</div>
            <div class="c-val green">{{ $rupiah($totalPemasukan) }}</div>
        </td>
        <td>
            <div class="c-lbl">Sisa Piutang (Acara Periode Ini)</div>
            <div class="c-val red">{{ $rupiah($totalPiutang) }}</div>
        </td>
        <td>
            <div class="c-lbl">Jumlah Acara</div>
            <div class="c-val">{{ $pemesanans->count() }}</div>
        </td>
    </tr>
</table>

<div class="sec-title">Rincian Pembayaran Diterima</div>
<table class="data">
    <thead>
        <tr><th>Tanggal</th><th>Klien</th><th>Jenis</th><th>Metode</th><th class="amt">Jumlah</th></tr>
    </thead>
    <tbody>
        @forelse ($pembayarans as $p)
            <tr>
                <td>{{ $p->tanggal_bayar->translatedFormat('d M Y') }}</td>
                <td>{{ $p->pemesanan?->nama_klien ?? '—' }}</td>
                <td>{{ $p->jenis_label }}</td>
                <td>{{ $p->metode ?: '—' }}</td>
                <td class="amt">{{ $rupiah($p->jumlah) }}</td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align:center;color:#999;">Tidak ada pembayaran pada periode ini.</td></tr>
        @endforelse
    </tbody>
    @if ($pembayarans->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;font-weight:bold;border-top:1.5px solid #161616;">TOTAL PEMASUKAN</td>
                <td class="amt green" style="border-top:1.5px solid #161616;">{{ $rupiah($totalPemasukan) }}</td>
            </tr>
        </tfoot>
    @endif
</table>

<div class="sec-title">Acara pada Periode Ini</div>
<table class="data">
    <thead>
        <tr><th>Tgl Acara</th><th>Kode</th><th>Klien</th><th class="amt">Total</th><th class="amt">Terbayar</th><th class="amt">Sisa</th></tr>
    </thead>
    <tbody>
        @forelse ($pemesanans as $b)
            <tr>
                <td>{{ $b->tanggal_acara->translatedFormat('d M Y') }}</td>
                <td>{{ $b->kode }}</td>
                <td>{{ $b->nama_klien }}</td>
                <td class="amt">{{ $rupiah($b->total) }}</td>
                <td class="amt">{{ $rupiah($b->terbayar) }}</td>
                <td class="amt">{{ $rupiah($b->sisa) }}</td>
            </tr>
        @empty
            <tr><td colspan="6" style="text-align:center;color:#999;">Tidak ada acara pada periode ini.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="foot">
    Dicetak otomatis oleh Sistem Informasi Manajemen Taf Wedding pada {{ now()->translatedFormat('d F Y, H:i') }} WIB
</div>
</body>
</html>