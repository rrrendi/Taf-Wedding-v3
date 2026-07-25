@extends('layouts.public')

@section('title', 'Pesanan Terkirim — Taf Wedding')

@section('content')
    @include('client.partials.topbar')
    @php
        $terbayar = $pemesanan->terbayar;
        $stepKonfirmasi = in_array($pemesanan->status, ['dikonfirmasi', 'selesai']) ? 'now' : 'wait';
    @endphp

    <div class="success-shell">
        <div class="success-mark">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6">
                <path d="M20 6 9 17l-5-5" />
            </svg>
        </div>
        <div style="font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--gold2);">
            Pesanan Terkirim</div>
        <h1
            style="font-family:var(--serif);font-size:clamp(24px,4vw,30px);color:var(--ink);margin-top:6px;line-height:1.25;">
            Terima Kasih, Pemesanan<br>Anda Sudah Kami Terima</h1>
        <p style="color:var(--muted);font-size:13.5px;margin-top:10px;max-width:400px;margin-left:auto;margin-right:auto;">
            Konfirmasi telah dikirim ke WhatsApp Anda. Tim kami akan meninjau dan mengonfirmasi jadwal dalam 1×24 jam.
        </p>
        <div class="success-code">{{ $pemesanan->kode }}</div>

        <div class="thread-stepper">
            <div class="thread-node done">
                <div class="thread-node-dot"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3">
                        <path d="M20 6 9 17l-5-5" />
                    </svg></div>
                <div class="thread-node-label">Diterima</div>
            </div>
            <div class="thread-line done">
                <div class="thread-line-in"></div>
            </div>
            <div class="thread-node {{ $stepKonfirmasi }}">
                <div class="thread-node-dot">2</div>
                <div class="thread-node-label">Ditinjau Admin</div>
            </div>
            <div class="thread-line">
                <div class="thread-line-in"></div>
            </div>
            <div class="thread-node wait">
                <div class="thread-node-dot">3</div>
                <div class="thread-node-label">Pembayaran DP</div>
            </div>
            <div class="thread-line">
                <div class="thread-line-in"></div>
            </div>
            <div class="thread-node wait">
                <div class="thread-node-dot">4</div>
                <div class="thread-node-label">Terjadwal</div>
            </div>
        </div>

        <div class="card" style="text-align:left;padding:18px;margin-top:26px;">
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:14px;"><span
                    style="color:var(--ink3);">Nama</span><span style="font-weight:600;">{{ $pemesanan->nama_klien }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:14px;"><span
                    style="color:var(--ink3);">Tanggal</span><span
                    style="font-weight:600;">{{ $pemesanan->tanggal_acara->translatedFormat('l, d F Y') }}</span></div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:14px;"><span
                    style="color:var(--ink3);">Lokasi</span><span style="font-weight:600;">{{ $pemesanan->lokasi }}</span>
            </div>
            <div
                style="display:flex;justify-content:space-between;padding-top:12px;margin-top:6px;border-top:1.5px dashed var(--border);font-size:15px;">
                <span style="color:var(--ink3);">Estimasi Total</span><span
                    style="font-weight:800;color:var(--goldDeep);">{{ $pemesanan->total_format }}</span>
            </div>
        </div>

        <div class="success-actions">
            <a href="{{ route('client.pemesanan.show', $pemesanan) }}" class="btn btn-gold btn-lg">Lihat Detail Pesanan</a>
            <a href="{{ route('landing') }}" class="btn btn-outline btn-lg">Kembali ke Beranda</a>
        </div>
    </div>
@endsection