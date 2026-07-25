@extends('layouts.admin')

@section('title', 'Jadwal Event — Taf Wedding')

@section('content')
    @php
        $prev = (clone $awal)->subMonth();
        $next = (clone $awal)->addMonth();
        $today = now();
    @endphp
    <div class="fade">
        <div class="pg-head">
            <div>
                <h1>Jadwal Event</h1>
                <p>Kalender acara terkonfirmasi</p>
            </div>
        </div>

        {{-- MEMBAGI KALENDER DAN EVENT MENJADI 2 KOLOM --}}
        <div class="grid-jadwal">
            
            {{-- KOLOM KIRI: Kalender --}}
            <div class="card" style="margin-bottom:0;">
                <div class="card-b">
                    <div class="flex-between" style="margin-bottom:14px;">
                        {{-- PERBAIKAN: Mengganti teks &larr; menjadi SVG tebal --}}
                        <a href="{{ route('admin.jadwal.index', ['bulan' => $prev->month, 'tahun' => $prev->year]) }}"
                            class="btn btn-outline btn-sm" style="padding: 6px 12px; display: flex; align-items: center; justify-content: center;">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        </a>
                        
                        <span
                            style="font-family:var(--serif);font-size:19px;font-weight:600;color:var(--ink);">{{ $awal->translatedFormat('F Y') }}</span>
                        
                        {{-- PERBAIKAN: Mengganti teks &rarr; menjadi SVG tebal --}}
                        <a href="{{ route('admin.jadwal.index', ['bulan' => $next->month, 'tahun' => $next->year]) }}"
                            class="btn btn-outline btn-sm" style="padding: 6px 12px; display: flex; align-items: center; justify-content: center;">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="cal">
                        @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $d)<div class="cal-hd">{{ $d }}</div>
                        @endforeach
                        @for ($i = 0; $i < $hariPertama; $i++)
                        <div class="cal-d nil"></div>@endfor
                        @for ($d = 1; $d <= $jumlahHari; $d++)
                            @php
                                $events = $eventsPerHari->get($d);
                                $isToday = $today->year === $awal->year && $today->month === $awal->month && $today->day === $d;
                                $cls = 'cal-d' . ($events ? ' ev' : '') . ($isToday ? ' today' : '');
                                $title = $events ? $events->pluck('nama_klien')->implode(', ') : '';
                            @endphp
                            <div class="{{ $cls }}" title="{{ $title }}">
                                @if ($events)<span class="cal-count">{{ $events->count() }}</span>@endif<span>{{ $d }}</span>
                            </div>
                        @endfor
                    </div>

                    <div class="cal-legend"
                        style="margin-top:16px; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                        <span class="ci" style="display:inline-flex; align-items:center; gap:6px;">
                            <span class="cal-sw"
                                style="display:inline-block; width:12px; height:12px; min-width:12px; flex:0 0 12px; border-radius:3px; background:var(--bg2); border:1px solid var(--border2);"></span>
                            Kosong
                        </span>
                        <span class="ci" style="display:inline-flex; align-items:center; gap:6px;">
                            <span class="cal-sw book" style="display:inline-block; width:12px; height:12px; min-width:12px; flex:0 0 12px; border-radius:3px;"></span> Ada Event
                        </span>
                        <span class="ci" style="display:inline-flex; align-items:center; gap:6px;">
                            <span class="cal-sw now" style="display:inline-block; width:12px; height:12px; min-width:12px; flex:0 0 12px; border-radius:3px;"></span> Hari Ini
                        </span>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: Event Mendatang --}}
            <div class="card" style="margin-bottom:0; align-self:start;">
                <div class="card-h"><span class="card-t">Event Mendatang</span></div>
                <div class="tbl-wrap">
                    <table class="rtable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Klien</th>
                                <th>Venue</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($eventMendatang as $b)
                                @php $hari = (int) now()->startOfDay()->diffInDays($b->tanggal_acara, false); @endphp
                                <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.pemesanan.show', $b) }}'">
                                    <td data-label="Tanggal" style="font-weight:700;color:var(--gold2);">
                                        {{ $b->tanggal_acara->translatedFormat('d M Y') }}</td>
                                    <td data-label="Klien"><strong>{{ $b->nama_klien }}</strong></td>
                                    <td data-label="Venue" style="color:var(--muted);font-size:12.5px;">
                                        {{ Str::limit($b->lokasi, 26) }}</td>
                                    <td data-label="Status">
                                        @if ($hari >= 0 && $hari <= 14)<span class="badge b-orange">H-{{ $hari }}</span>@else<span
                                        class="badge b-green">Terjadwal</span>@endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="muted" style="text-align:center;padding:26px;">Belum ada event mendatang.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection