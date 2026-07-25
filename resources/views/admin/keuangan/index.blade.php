@extends('layouts.admin')

@section('title', 'Keuangan — Taf Wedding')

@section('content')
@php
    $maxBulan = max(1, max($pemasukanBulanan));
    $bulanIni = now()->month;
@endphp
<div class="fade">
    <div class="pg-head">
        <div><h1>Keuangan</h1><p>Ringkasan pemasukan & tagihan</p></div>
        <form method="GET" action="{{ route('admin.keuangan.laporan') }}" class="flex-gap">
            <select class="input" name="bulan" style="padding:9px 12px;font-size:13px;">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" @selected($m === $bulanIni)>{{ $namaBulan[$m] }}</option>
                @endfor
            </select>
            <input type="hidden" name="tahun" value="{{ $tahun }}">
            <button class="btn btn-gold btn-sm" type="submit">Cetak PDF</button>
        </form>
    </div>

    <div class="stats" style="grid-template-columns:repeat(2,1fr);">
        <div class="st">
            <div class="st-ico" style="background:var(--greenBg);color:var(--green);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2.5" y="6" width="19" height="13" rx="2.5"/><path d="M2.5 10h19M17 15h.01"/></svg>
            </div>
            <div class="st-lbl">Uang Masuk</div>
            <div class="st-val" style="color:var(--green);">Rp {{ number_format($totalDiterima, 0, ',', '.') }}</div>
            <div class="st-sub" style="color:var(--green);">{{ $jumlahLunas }} klien lunas</div>
        </div>
        <div class="st">
            <div class="st-ico" style="background:var(--redBg);color:var(--red);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            </div>
            <div class="st-lbl">Belum Terbayar</div>
            <div class="st-val" style="color:var(--red);">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</div>
            <div class="st-sub" style="color:var(--red);">Perlu ditagih</div>
        </div>
        <div class="st">
            <div class="st-ico" style="background:var(--goldBg);color:var(--gold2);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m7 13 4-4 3 3 5-6"/></svg>
            </div>
            <div class="st-lbl">Total Kontrak</div>
            <div class="st-val">Rp {{ number_format($totalKontrak, 0, ',', '.') }}</div>
            <div class="st-sub" style="color:var(--gold2);">{{ $pemesanans->count() }} pesanan</div>
        </div>
        <div class="st">
            <div class="st-ico" style="background:var(--blueBg);color:var(--blue);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <div class="st-lbl">Sudah Lunas</div>
            <div class="st-val">{{ $jumlahLunas }}</div>
            <div class="st-sub" style="color:var(--blue);">pesanan</div>
        </div>
    </div>

    <div class="card">
        <div class="card-h"><span class="card-t">Pemasukan per Bulan</span><span style="font-size:11px;color:var(--muted);">{{ $tahun }}</span></div>
        <div class="card-b">
            <div class="bars">
                @for ($m = 1; $m <= 12; $m++)
                    @php
                        $val = $pemasukanBulanan[$m];
                        $tinggi = ($val / $maxBulan) * 100;
                        $juta = $val >= 1000000 ? round($val / 1000000, 1) : round($val / 1000);
                        $satuan = $val >= 1000000 ? 'jt' : ($val > 0 ? 'rb' : '');
                        $isNow = $m === $bulanIni;
                    @endphp
                    <div class="bar-c">
                        <div class="bar-v">{{ $val > 0 ? $juta . $satuan : '' }}</div>
                        <div class="bar" style="height:{{ max(2, $tinggi) }}%;background:{{ $isNow ? 'var(--gold-grad)' : 'linear-gradient(to top,#6a5d49,#8a7a5e)' }};transition-delay:{{ $m * 0.04 }}s;"></div>
                        <div class="bar-l">{{ $namaBulan[$m] }}</div>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-h"><span class="card-t">Rincian per Klien</span></div>
        <div class="tbl-wrap">
            <table class="rtable">
                <thead><tr><th>Klien</th><th>Total</th><th>Dibayar</th><th>Sisa</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($pemesanans->sortByDesc('created_at') as $b)
                        <tr>
                            <td data-label="Klien"><strong>{{ $b->nama_klien }}</strong></td>
                            <td data-label="Total">{{ $b->total_format }}</td>
                            <td data-label="Dibayar" style="color:var(--green);font-weight:600;">{{ $b->terbayar_format }}</td>
                            <td data-label="Sisa" style="color:{{ $b->sisa > 0 ? 'var(--red)' : 'var(--muted)' }};font-weight:600;">{{ $b->sisa_format }}</td>
                            <td data-label="Status"><span class="badge {{ $b->status_bayar === 'lunas' ? 'b-green' : ($b->status_bayar === 'dp' ? 'b-blue' : 'b-red') }}">{{ $b->status_bayar_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted" style="text-align:center;">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
