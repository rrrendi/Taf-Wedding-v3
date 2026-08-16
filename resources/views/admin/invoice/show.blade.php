<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
@php
    // Embed gambar sebagai data-URI agar selalu tampil di dompdf (anti masalah path).
    $logoFile  = public_path('images/taf-invoice-logo.jpg');
    $splashFile= public_path('images/invoice-splash.jpg');
    $wtFile    = public_path('images/wt-mark.png');
    $logoSrc   = is_file($logoFile)   ? 'data:image/jpeg;base64,'.base64_encode(file_get_contents($logoFile))   : null;
    $splashSrc = is_file($splashFile) ? 'data:image/jpeg;base64,'.base64_encode(file_get_contents($splashFile)) : null;
    $wtSrc     = is_file($wtFile)     ? 'data:image/png;base64,'.base64_encode(file_get_contents($wtFile))      : null;

    $rupiah = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $grup = ['paket_wedding'=>'Paket Wedding','makeup_only'=>'Makeup Only','tambahan'=>'Tambahan'];
    $byKategori = $pemesanan->layanans->groupBy('kategori');
@endphp
<style>
    @page { margin: 24px 32px; }
    * { box-sizing: border-box; }
    body { font-family: Helvetica, sans-serif; color: #1a1a1a; font-size: 11px; line-height: 1.45; }
    table { width: 100%; border-collapse: collapse; }
    td { vertical-align: top; }

    .logo-img { width: 158px; height: auto; border-radius: 4px; }
    .brand-cell { border-radius:6px; }
    .brand-pad { padding: 24px 14px; text-align:center; border-radius:6px;
        background-color:#FBEDED; @if($splashSrc) background-image:url('{{ $splashSrc }}'); @endif
        background-repeat:no-repeat; background-size:100% 100%; }
    .brand-name { font-family:'Times', serif; font-size:30px; font-weight:bold; letter-spacing:2px; color:#C0596A; }
    .brand-role { font-family:'Times', serif; font-style:italic; font-size:18px; color:#5a4a4a; margin-top:6px; }

    .addr { font-size:9.5px; color:#333; letter-spacing:.4px; margin-top:11px; }
    .inv-title { text-align:center; font-family:'Times', serif; font-size:28px; font-weight:bold; color:#161616; letter-spacing:2px; margin-top:10px; }
    .inv-no { text-align:center; font-size:12px; color:#444; letter-spacing:1px; margin-bottom:6px; }

    .lbl { font-size:11px; font-weight:bold; letter-spacing:1.5px; color:#161616; }
    .val-line { border-bottom:1px dotted #999; padding:3px 0; font-size:11px; min-height:16px; }

    .tbl-head th { text-align:left; font-size:11px; font-weight:bold; letter-spacing:1px; color:#161616;
        border-bottom:1.6px solid #161616; padding:7px 4px; }
    .grp { font-family:'Times', serif; font-size:14px; color:#444; padding:9px 4px 3px; letter-spacing:.5px; }
    .item td { padding:3px 4px; border-bottom:1px dotted #c9c9c9; font-size:10.5px; }
    .item .num { text-align:center; }
    .item .amt { text-align:right; }
    .grp-empty td { padding:3px 4px; border-bottom:1px dotted #dadada; height:14px; }

    .pay-title { font-family:'Times', serif; font-weight:bold; font-size:13px; color:#161616; margin-bottom:3px; }
    .pay-block { font-size:9.5px; line-height:1.55; margin-bottom:8px; }
    .pay-block b { font-size:10px; }

    .totbox td { padding:6px 2px; }
    .totlbl { font-family:'Times', serif; font-weight:bold; font-size:14px; text-align:right; color:#161616; }
    .totval { border-bottom:1px dotted #777; text-align:right; font-size:12px; font-weight:bold; padding-left:8px; }
    .grand .totlbl, .grand .totval { color:#A87C2E; }
    .ket-note { font-size:8.5px; color:#555; margin-top:7px; line-height:1.45; }

    .tc-title { font-weight:bold; font-size:10px; margin-bottom:3px; }
    .tc { font-size:8px; color:#333; line-height:1.5; }
    .sign-wrap { text-align:center; }
    .sign-mark { width:78px; height:auto; margin:0 auto 2px; }
    .sign { font-family:'Times', serif; font-style:italic; font-size:21px; color:#B07F2E; }
    .thanks { text-align:center; font-weight:bold; font-size:11px; letter-spacing:1px; margin-top:3px; color:#161616; }
</style>
</head>
<body>

{{-- ════════ HEADER ════════ --}}
<table>
    <tr>
        <td style="width:172px;">
            @if ($logoSrc)
                <img class="logo-img" src="{{ $logoSrc }}" alt="Taf Wedding">
            @else
                <div style="width:158px;height:110px;background:#0d0d0d;color:#d9bc8e;text-align:center;border-radius:4px;padding:14px 6px;">
                    <div style="font-family:'Times',serif;font-size:30px;font-weight:bold;letter-spacing:3px;">TAF</div>
                    <div style="font-size:9px;margin-top:30px;border-top:1px solid #5c4a2e;padding-top:6px;">TAF WEDDING by WAODE</div>
                </div>
            @endif
        </td>
        <td style="padding-left:14px;">
            <table class="brand-cell"><tr><td class="brand-pad">
                <div class="brand-name">WAODE TRISMAWATI</div>
                <div class="brand-role">Profesional Makeup Artist</div>
            </td></tr></table>
        </td>
    </tr>
</table>

<div class="addr">TAMAN HOLIS INDAH BELAKANG BLOK C1.NO.6 KP. MAHKELUNG CIGONDEWAH RAHAYU KOTA BANDUNG</div>
<div class="addr" style="margin-top:1px;">GALLERY : THE GPA LUXURY CLUSTER ARRAYA BLOK E-20 BALEENDAH</div>

<div class="inv-title">INVOICE</div>
<div class="inv-no">No: {{ $noInvoice }}</div>

{{-- ════════ CUSTOMER & ACARA ════════ --}}
<table style="margin-top:6px;">
    <tr>
        <td style="width:48%; padding-right:18px;">
            <div class="lbl">CUSTOMER'S</div>
            <div class="val-line" style="margin-top:6px;">{{ $pemesanan->nama_klien }}</div>
            <div class="val-line">{{ $pemesanan->phone }}</div>
            <div class="val-line">{{ $pemesanan->email }}</div>
        </td>
        <td style="width:52%;">
            <div class="lbl">JENIS ACARA :</div>
            <div class="val-line" style="margin-top:6px;">{{ $pemesanan->jenis_acara_label }}</div>
            <div class="lbl" style="margin-top:8px;">ACARA TANGGAL :</div>
            <div class="val-line" style="margin-top:6px;">{{ $pemesanan->tanggal_acara->translatedFormat('l, d F Y') }}</div>
            <div class="lbl" style="margin-top:8px;">LOKASI ACARA :</div>
            <div class="val-line" style="margin-top:6px;">{{ $pemesanan->lokasi }}</div>
        </td>
    </tr>
</table>

{{-- ════════ TABEL LAYANAN ════════ --}}
<table style="margin-top:14px;">
    <tr class="tbl-head">
        <th style="width:64%;">KETERANGAN</th>
        <th style="width:14%; text-align:center;">QTY</th>
        <th style="width:22%; text-align:right;">TOTAL</th>
    </tr>
</table>

@foreach ($grup as $key => $label)
    <table><tr><td class="grp" colspan="3">{{ $label }}</td></tr></table>
    <table>
        @forelse (($byKategori[$key] ?? collect()) as $l)
            <tr class="item">
                <td style="width:64%;">{{ $l->nama }}</td>
                <td class="num" style="width:14%;">{{ $l->pivot->qty }}</td>
                <td class="amt" style="width:22%;">{{ $rupiah($l->pivot->subtotal) }}</td>
            </tr>
        @empty
            <tr class="grp-empty"><td colspan="3"></td></tr>
            <tr class="grp-empty"><td colspan="3"></td></tr>
        @endforelse
    </table>
@endforeach

{{-- ════════ PEMBAYARAN & TOTAL ════════ --}}
<table style="margin-top:16px; border-top:1.6px solid #161616;">
    <tr>
        <td style="width:54%; padding-top:12px; padding-right:14px;">
            <div class="pay-title">Pembayaran Transfer Bank</div>
            <div class="pay-block"><b>BANK BCA</b><br>3790970682<br>A.n Waode Trismawati</div>
            <div class="pay-block"><b>SEABANK</b><br>901322162960<br>A.n Waode Trismawati</div>
            <div class="pay-title">Pembayaran Virtual Account</div>
            <div class="pay-block"><b>DANA - SHOPEE PAY - ASTRAPAY - OVO - GOPAY</b><br>085794366898</div>
        </td>
        <td style="width:46%; padding-top:12px;">
            <table class="totbox">
                <tr class="grand">
                    <td class="totlbl" style="width:58%;">TOTAL</td>
                    <td class="totval" style="width:42%;">{{ $rupiah($pemesanan->total) }}</td>
                </tr>
                <tr>
                    <td class="totlbl">DOWN PAYMET (DP)</td>
                    <td class="totval">{{ $rupiah($pemesanan->terbayar) }}</td>
                </tr>
                <tr>
                    <td class="totlbl">SISA PELUNASAN/BAYAR</td>
                    <td class="totval">{{ $rupiah($pemesanan->sisa) }}</td>
                </tr>
            </table>
            <div class="ket-note">
                <b>Keterangan :</b> Uang yang sudah ditransfer/dibayarkan <b>Tidak Dapat Dikembalikan</b>
                dengan alasan apapun dan apabila <b>CANCEL sepihak Otomatis HANGUS</b>.
            </div>
        </td>
    </tr>
</table>

{{-- ════════ TERM & CONDITION + TANDA TANGAN ════════ --}}
<table style="margin-top:14px;">
    <tr>
        <td style="width:62%; padding-right:14px;">
            <div class="tc-title">Term &amp; Condition</div>
            <div class="tc">
                1. Pembayaran Hanya ke <b>No.Rekening A.n Waode Trismawati</b> jika <b>Transaksi</b> selain itu <b>Bukan Tanggung Jawab kami</b><br>
                2. Minimal Booking <b>50%</b> dan <b>Pelunasan Wedding Maksimal H-10 &amp; Pelunasan Makeup H-2</b><br>
                3. Apabila ada <b>Penambahan Maksimal Konfirmasi H-3</b> untuk <b>Paket Wedding/Makeup</b><br>
                4. Untuk Pembayaran <b>Transfer wajib kirimkan Bukti Berhasil (Screen shoot/Bukti Slip)</b><br>
                5. Apabila Barang ada yang <b>RUSAK atau HILANG</b> merupakan <b>Tanggung Jawab Customer</b> dan <b>HARUS DIGANTI</b><br>
                6. semua <b>Property/Barang</b> sifatnya <b>SEWA</b>
            </div>
        </td>
        <td style="width:38%; text-align:center; vertical-align:bottom;">
            <div class="sign-wrap">
                @if ($wtSrc)<img class="sign-mark" src="{{ $wtSrc }}" alt="">@endif
                <div class="sign">Waode Trismawati</div>
            </div>
            <div class="thanks">Thank you</div>
        </td>
    </tr>
</table>

</body>
</html>