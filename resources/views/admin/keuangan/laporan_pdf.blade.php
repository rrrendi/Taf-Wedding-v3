<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<style>
    @page { margin: 32px 38px; }
    body { font-family: 'Helvetica', sans-serif; color: #1b1b1b; font-size: 11px; }
    .head { text-align: center; border-bottom: 2px solid #161616; padding-bottom: 10px; margin-bottom: 16px; }
    .brand { font-family: 'Times', serif; font-size: 22px; font-weight: bold; letter-spacing: 1px; }
    .brand em { color: #a07d4f; font-style: italic; }
    .sub { font-size: 10px; color: #555; margin-top: 2px; }
    .title { font-size: 14px; font-weight: bold; margin-top: 8px; letter-spacing: 1px; }
    .period { font-size: 11px; color: #444; margin-top: 2px; }

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
@php $rupiah = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.'); @endphp

<div class="head">
    <div class="brand">Taf <em>Wedding</em></div>
    <div class="sub">Waode Trismawati — Wedding Organizer &amp; Makeup Artist · Bandung</div>
    <div class="title">LAPORAN KEUANGAN</div>
    <div class="period">Periode: {{ $awal->translatedFormat('F Y') }}</div>
</div>

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
