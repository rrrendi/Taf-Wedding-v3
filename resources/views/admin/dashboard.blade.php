@extends('layouts.admin')

@section('title', 'Dashboard — Taf Wedding')

@section('content')
    <div class="fade">
        <div class="pg-head">
            <div>
                <h1>Dashboard</h1>
                <p>Ringkasan singkat bisnis Anda hari ini</p>
            </div>
        </div>

        {{-- Banner tindakan bila ada pesanan menunggu --}}
        @if ($pending > 0)
            <a href="{{ route('admin.pemesanan.index', ['status' => 'pending']) }}" style="display:block;">
                <div class="alert alert-warn" style="cursor:pointer;align-items:center;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 2" />
                    </svg>
                    <span><strong>{{ $pending }} pesanan</strong> menunggu konfirmasi Anda. Ketuk untuk meninjau &rarr;</span>
                </div>
            </a>
        @endif

        {{-- STAT --}}
        <div class="stats">
            <div class="st">
                <div class="st-ico" style="background:var(--goldBg);color:var(--gold2);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                        <path d="m9 14 2 2 4-4" />
                    </svg>
                </div>
                <div class="st-lbl">Total Pesanan</div>
                <div class="st-val">{{ $totalPesanan }}</div>
                <div class="st-sub" style="color:var(--green);">{{ $dikonfirmasi }} terkonfirmasi</div>
            </div>
            <div class="st">
                <div class="st-ico" style="background:var(--orangeBg);color:var(--orange);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 2" />
                    </svg>
                </div>
                <div class="st-lbl">Menunggu Konfirmasi</div>
                <div class="st-val">{{ $pending }}</div>
                <div class="st-sub" style="color:var(--orange);">Perlu ditindak</div>
            </div>
            <div class="st">
                <div class="st-ico" style="background:var(--greenBg);color:var(--green);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <rect x="2.5" y="6" width="19" height="13" rx="2.5" />
                        <path d="M2.5 10h19M17 15h.01" />
                    </svg>
                </div>
                <div class="st-lbl">Uang Masuk</div>
                <div class="st-val">Rp {{ number_format($pemasukan, 0, ',', '.') }}</div>
                <div class="st-sub" style="color:var(--green);">Sudah diterima</div>
            </div>
            <div class="st">
                <div class="st-ico" style="background:var(--redBg);color:var(--red);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 3v18h18" />
                        <path d="m7 14 4-4 3 3 5-6" />
                    </svg>
                </div>
                <div class="st-lbl">Belum Terbayar</div>
                <div class="st-val">Rp {{ number_format($piutang, 0, ',', '.') }}</div>
                <div class="st-sub" style="color:var(--red);">Perlu ditagih</div>
            </div>
        </div>

        {{-- TREN PEMASUKAN 7 HARI --}}
        <div class="card">
            <div class="card-h"><span class="card-t">Pemasukan 7 Hari Terakhir</span></div>
            <div class="card-b">
                @php $maxHarian = max(1, $pemasukanHarian->max('jumlah')); @endphp
                <div class="bars">
                    @foreach ($pemasukanHarian as $i => $h)
                        @php
                            $tinggi = ($h['jumlah'] / $maxHarian) * 100;
                            $juta = $h['jumlah'] >= 1000000 ? round($h['jumlah'] / 1000000, 1) : round($h['jumlah'] / 1000);
                            $satuan = $h['jumlah'] >= 1000000 ? 'jt' : ($h['jumlah'] > 0 ? 'rb' : '');
                            $isNow = $i === $pemasukanHarian->count() - 1;
                        @endphp
                        <div class="bar-c">
                            <div class="bar-v">{{ $h['jumlah'] > 0 ? $juta . $satuan : '' }}</div>
                            <div class="bar"
                                style="height:{{ max(2, $tinggi) }}%;background:{{ $isNow ? 'var(--gold-grad)' : 'linear-gradient(to top,#6a5d49,#8a7a5e)' }};transition-delay:{{ $i * 0.05 }}s;">
                            </div>
                            <div class="bar-l">{{ $h['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- PESANAN TERBARU (satu tabel jelas) --}}
        <div class="card">
            <div class="card-h">
                <span class="card-t">Pesanan Terbaru</span>
                <a href="{{ route('admin.pemesanan.index') }}" class="pill-link">Lihat semua &rarr;</a>
            </div>
            <div class="tbl-wrap">
                <table class="rtable">
                    <thead>
                        <tr>
                            <th>Klien</th>
                            <th>Tanggal Acara</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesananTerbaru as $b)
                            <tr>
                                <td data-label="Klien"><strong>{{ $b->nama_klien }}</strong>
                                    <div style="font-size:11px;color:var(--gold2);font-weight:600;">{{ $b->kode }}</div>
                                </td>
                                <td data-label="Tanggal">{{ $b->tanggal_acara->translatedFormat('d M Y') }}</td>
                                <td data-label="Status"><span
                                        class="badge {{ $b->status === 'dikonfirmasi' ? 'b-green' : ($b->status === 'dibatalkan' ? 'b-red' : ($b->status === 'selesai' ? 'b-blue' : 'b-orange')) }}">{{ $b->status_label }}</span>
                                </td>
                                <td data-label="Pembayaran"><span
                                        class="badge {{ $b->status_bayar === 'lunas' ? 'b-green' : ($b->status_bayar === 'dp' ? 'b-blue' : 'b-red') }}">{{ $b->status_bayar_label }}</span>
                                </td>
                                <td data-label="" class="cell-actions"><a href="{{ route('admin.pemesanan.show', $b) }}"
                                        class="btn btn-outline btn-sm">Kelola</a></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="muted" style="text-align:center;padding:28px;">Belum ada pesanan masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection