@extends('layouts.public')

@section('title', 'Portal Klien — Taf Wedding')

@section('content')
@include('client.partials.topbar')
@php
    $aktif = $pemesanans->firstWhere(fn ($p) => in_array($p->status, ['pending', 'dikonfirmasi']));
    $accentMap = ['dikonfirmasi' => 'var(--green)', 'selesai' => 'var(--blue)', 'dibatalkan' => 'var(--red)'];
    $badgeMap = ['dikonfirmasi' => 'b-green', 'selesai' => 'b-blue', 'dibatalkan' => 'b-red'];
    $bayarBadgeMap = ['lunas' => 'b-green', 'dp' => 'b-blue'];
@endphp

<div class="cl-page fade" style="padding-top:10px;">
    <div class="page-head" style="display:flex;justify-content:space-between;align-items:flex-end;gap:14px;flex-wrap:wrap;margin-bottom:20px;">
        <div>
            <h1 style="font-family:var(--serif);font-size:clamp(22px,3vw,28px);color:var(--ink);">Portal Klien</h1>
            <p style="font-size:13px;color:var(--ink4);margin-top:4px;">Selamat datang kembali, <b style="color:var(--ink2);">{{ auth()->user()->name }}</b>. Kelola dan pantau pesanan acara Anda di sini.</p>
        </div>
        @if ($aktif)
            @php $pesanAktifMsg = 'Anda masih memiliki pesanan aktif (' . $aktif->kode . '). Selesaikan atau batalkan pesanan tersebut terlebih dahulu sebelum membuat yang baru.'; @endphp
            <button type="button" class="btn btn-gold"
                onclick="tafNotifPesananAktif('{{ $pesanAktifMsg }}', '{{ route('client.pemesanan.show', $aktif) }}')">+
                Buat Pesanan Baru</button>
        @else
            <a href="{{ route('client.pemesanan.create') }}" class="btn btn-gold">+ Buat Pesanan Baru</a>
        @endif
    </div>

    @if ($pemesanans->count() > 0)
        <div class="stat-row">
            <div class="stat-chip">
                <div class="ico"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
                <div><div class="lbl">Pesanan Aktif</div><div class="val">{{ $aktif->kode ?? 'Tidak ada' }}</div></div>
            </div>
            <div class="stat-chip">
                <div class="ico"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-6.5 7-11a7 7 0 1 0-14 0c0 4.5 7 11 7 11Z"/></svg></div>
                <div><div class="lbl">Acara Terdekat</div><div class="val">{{ $aktif ? $aktif->tanggal_acara->translatedFormat('d F Y') : '—' }}</div></div>
            </div>
            <div class="stat-chip">
                <div class="ico"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M4 9h16M9 4v16"/></svg></div>
                <div><div class="lbl">Status Bayar</div><div class="val">{{ $aktif ? ($aktif->status_bayar === 'lunas' ? 'Lunas' : ($aktif->status_bayar === 'dp' ? 'DP Terbayar' : 'Belum Bayar')) : '—' }}</div></div>
            </div>
            <div class="stat-chip">
                <div class="ico"><svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                <div><div class="lbl">Total Riwayat</div><div class="val">{{ $pemesanans->total() }} pesanan</div></div>
            </div>
        </div>

        <div class="notice-bar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
            <span>Anda dapat memiliki maksimal <b>1 pesanan aktif</b> dalam satu waktu. Selesaikan atau batalkan pesanan berjalan sebelum membuat yang baru.</span>
        </div>

        <div class="booking-grid">
            @foreach ($pemesanans as $p)
                <a href="{{ route('client.pemesanan.show', $p) }}" class="ticket">
                    <div class="ticket-accent" style="background:{{ $accentMap[$p->status] ?? 'var(--orange)' }};"></div>
                    <div class="ticket-stub">
                        <span class="ticket-kode">{{ $p->kode }}</span>
                        <span class="badge {{ $badgeMap[$p->status] ?? 'b-orange' }}">{{ $p->status_label }}</span>
                    </div>
                    <div class="ticket-perf"></div>
                    <div class="ticket-body">
                        <div class="booking-card-name">{{ $p->nama_klien }}</div>
                        <div class="booking-meta">
                            <div class="row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                <span>{{ $p->tanggal_acara->translatedFormat('d F Y') }}</span>
                            </div>
                            <div class="row">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-6.5 7-11a7 7 0 1 0-14 0c0 4.5 7 11 7 11Z"/></svg>
                                <span>{{ $p->lokasi }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="ticket-foot">
                        <div>
                            <div class="booking-total-lbl">Total Kontrak</div>
                            <div class="booking-total-val">{{ $p->total_format }}</div>
                        </div>
                        <span class="badge {{ $bayarBadgeMap[$p->status_bayar] ?? 'b-red' }}">
                            {{ $p->status_bayar === 'lunas' ? 'Lunas' : ($p->status_bayar === 'dp' ? 'DP Terbayar' : 'Belum Bayar') }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($pemesanans->hasPages())
            <div style="margin-top:20px;">{{ $pemesanans->links() }}</div>
        @endif
    @else
        <div class="empty-state">
            <div class="ico">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
            </div>
            <h3>Belum Ada Berkas Pesanan</h3>
            <p>Saat ini belum ada riwayat pemesanan layanan pada akun Anda. Mulai rangkai hari bahagia Anda sekarang.</p>
            <a href="{{ route('client.pemesanan.create') }}" class="btn btn-gold">Mulai Buat Pemesanan</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
    <script>
        function tafNotifPesananAktif(pesan, tujuan) {
            sessionStorage.setItem('tafNoticeAktif', pesan);
            window.location.href = tujuan;
        }
    </script>
@endpush