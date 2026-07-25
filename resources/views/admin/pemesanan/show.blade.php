@extends('layouts.admin')

@section('title', $pemesanan->kode . ' — Detail Pemesanan')

@section('content')
@php
    $st = $pemesanan->status;
    $simulate = config('fonnte.simulate') || empty(config('fonnte.token'));
    $steps = [
        ['key'=>'pending','label'=>'Menunggu'],
        ['key'=>'dikonfirmasi','label'=>'Dikonfirmasi'],
        ['key'=>'selesai','label'=>'Selesai'],
    ];
    $order = ['pending'=>0,'dikonfirmasi'=>1,'selesai'=>2];
    $curIdx = $order[$st] ?? 0;
    $waBadge = fn($s) => $s==='terkirim' ? ['b-green','Terkirim'] : ($s==='gagal' ? ['b-red','Gagal'] : ['b-orange','Mode Demo']);
@endphp
<div class="fade">
    <div class="pg-head">
        <div>
            <h1>{{ $pemesanan->nama_klien }}</h1>
            <p>{{ $pemesanan->kode }} · dibuat {{ $pemesanan->created_at->translatedFormat('d F Y') }}</p>
        </div>
        <a href="{{ route('admin.pemesanan.index') }}" class="btn btn-ghost btn-sm">&larr; Kembali</a>
    </div>

    {{-- CARD STATUS UTAMA PADA PERANGKAT MOBILE --}}
    <div class="card" style="border-top: 3px solid var(--gold2);">
        <div class="card-b">
            @if ($st === 'dibatalkan')
                <div class="alert alert-error" style="margin-bottom:0;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="m15 9-6 6M9 9l6 6"/></svg>
                    <span>Pesanan ini <strong>dibatalkan</strong>. Tanggal sudah dilepas dari kalender.</span>
                    <form method="POST" action="{{ route('admin.pemesanan.status', $pemesanan) }}" onsubmit="return confirm('Aktifkan & konfirmasi ulang pesanan ini?')" style="margin-left:auto;">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="dikonfirmasi">
                        @if (session('konfirmasi_paksa'))<input type="hidden" name="paksa" value="1">@endif
                        <button type="submit" class="btn btn-outline btn-sm">Aktifkan Kembali</button>
                    </form>
                </div>
            @else
                <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:24px;">
                    {{-- Stepper Progress --}}
                    <div style="display:flex;align-items:center; flex:1; min-width:280px; max-width:400px;">
                        @foreach ($steps as $i => $step)
                            @php $done = $i < $curIdx; $active = $i === $curIdx; @endphp
                            <div style="display:flex;flex-direction:column;align-items:center;flex:1;">
                                <div style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;
                                    {{ $done ? 'background:var(--green);color:#fff;' : ($active ? 'background:var(--gold-grad);color:#2A1F08;box-shadow:0 4px 12px rgba(168,124,46,.35);' : 'background:var(--bg3);color:var(--muted);') }}">
                                    @if($done)<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>@else{{ $i+1 }}@endif
                                </div>
                                <div style="font-size:10.5px;font-weight:700;margin-top:6px;color:{{ $active ? 'var(--gold2)' : 'var(--muted)' }};text-align:center;">{{ $step['label'] }}</div>
                            </div>
                            @if(! $loop->last)<div style="height:2px;flex:0 0 18px;background:{{ $i < $curIdx ? 'var(--green)' : 'var(--border2)' }};margin-bottom:18px;"></div>@endif
                        @endforeach
                    </div>

                    {{-- Tombol Tindakan Cepat --}}
                    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
                        @if ($st === 'pending')
                            <form method="POST" action="{{ route('admin.pemesanan.status', $pemesanan) }}" onsubmit="return confirm('Batalkan pesanan ini?')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="dibatalkan">
                                <button type="submit" class="btn btn-outline" style="color:var(--red);border-color:var(--red);">Batalkan</button>
                            </form>
                            <form method="POST" action="{{ route('admin.pemesanan.status', $pemesanan) }}" onsubmit="return confirm('Konfirmasi pesanan {{ $pemesanan->kode }}? Jadwal akan dibuat & WhatsApp konfirmasi dikirim ke klien.')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="dikonfirmasi">
                                @if (session('konfirmasi_paksa'))<input type="hidden" name="paksa" value="1">@endif
                                <button type="submit" class="btn {{ session('konfirmasi_paksa') ? 'btn-red' : 'btn-green' }}">
                                    {{ session('konfirmasi_paksa') ? 'Tetap Konfirmasi (bentrok)' : 'Konfirmasi Pesanan' }}
                                </button>
                            </form>
                        @elseif ($st === 'dikonfirmasi')
                            <form method="POST" action="{{ route('admin.pemesanan.status', $pemesanan) }}" onsubmit="return confirm('Batalkan pesanan ini? Jadwal akan dilepas dari kalender.')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="dibatalkan">
                                <button type="submit" class="btn btn-outline" style="color:var(--red);border-color:var(--red);">Batalkan</button>
                            </form>
                            <form method="POST" action="{{ route('admin.pemesanan.status', $pemesanan) }}" onsubmit="return confirm('Tandai pesanan {{ $pemesanan->kode }} sebagai SELESAI? Status ini menandakan acara telah usai.')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="selesai">
                                <button type="submit" class="btn btn-green">Tandai Selesai</button>
                            </form>
                        @elseif ($st === 'selesai')
                            <span class="badge b-green" style="font-size:13px;padding:8px 16px;">Pesanan Selesai</span>
                        @endif
                    </div>
                </div>
            @endif

            @if ($pemesanan->jadwal && $st !== 'dibatalkan')
                <div style="margin-top:16px; padding-top:12px; border-top:1px dashed var(--border); display:flex; align-items:center; gap:7px; color:var(--muted); font-size:13px;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/></svg>
                    <span>Terjadwal: <strong>{{ $pemesanan->jadwal->tanggal_mulai->translatedFormat('d F Y') }}</strong></span>
                </div>
            @endif
        </div>
    </div>

    <div class="grid2">
        {{-- KIRI: Informasi Acara & Layanan --}}
        <div>
            <div class="card">
                <div class="card-h"><span class="card-t">Informasi Acara</span></div>
                <div class="card-b">
                    <div class="summary-line"><span class="muted">Mempelai</span><span><strong>{{ $pemesanan->nama_klien }}</strong></span></div>
                    <div class="summary-line"><span class="muted">Tanggal</span><span>{{ $pemesanan->tanggal_acara->translatedFormat('l, d M Y') }}</span></div>
                    <div class="summary-line"><span class="muted">Lokasi</span><span style="text-align:right;max-width:62%;">{{ $pemesanan->lokasi }}</span></div>
                    <div class="summary-line"><span class="muted">Jumlah Tamu</span><span>{{ $pemesanan->jumlah_tamu ?: '—' }}</span></div>
                    <div class="summary-line"><span class="muted">WhatsApp</span><span>{{ $pemesanan->phone }}</span></div>
                    <div class="summary-line"><span class="muted">Email</span><span>{{ $pemesanan->email ?: '—' }}</span></div>
                    @if ($pemesanan->catatan)
                        <div style="margin-top:13px;padding:12px 14px;background:var(--bg2);border-radius:var(--r3);font-size:13px;color:var(--ink3);"><strong>Catatan:</strong> {{ $pemesanan->catatan }}</div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-h"><span class="card-t">Layanan Dipesan</span></div>
                <div class="tbl-wrap">
                    <table class="rtable">
                        <thead><tr><th>Layanan</th><th>Qty</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            @foreach ($pemesanan->layanans as $l)
                                <tr>
                                    <td data-label="Layanan"><strong>{{ $l->nama }}</strong></td>
                                    <td data-label="Qty">{{ $l->pivot->qty }}</td>
                                    <td data-label="Subtotal">Rp {{ number_format((float) $l->pivot->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-b" style="border-top:1px solid var(--border);">
                    <div class="summary-line total"><span>Total Kontrak</span><span>{{ $pemesanan->total_format }}</span></div>
                    <div class="summary-line"><span class="muted">Terbayar</span><span style="color:var(--green);font-weight:700;">{{ $pemesanan->terbayar_format }}</span></div>
                    <div class="summary-line"><span class="muted">Sisa</span><span style="color:var(--red);font-weight:700;">{{ $pemesanan->sisa_format }}</span></div>
                </div>
                <div class="card-b" style="border-top:1px solid var(--border);display:flex;gap:9px;flex-wrap:wrap;">
                    <a href="{{ route('invoice.show', [$pemesanan, 'stream']) }}" target="_blank" class="btn btn-outline btn-sm">Lihat Invoice</a>
                    <a href="{{ route('invoice.show', [$pemesanan, 'download']) }}" class="btn btn-gold btn-sm">Unduh Invoice</a>
                </div>
            </div>
        </div>

        {{-- KANAN: Riwayat Pembayaran & WhatsApp --}}
        <div>
            <div class="card" x-data="{
                    open: {{ $errors->any() ? 'true' : 'false' }},
                    jenis: '',
                    jumlahRaw: '',
                    jumlahFormatted: '',
                    sisa: {{ (int) $pemesanan->sisa }},
                    autoFillJumlah() {
                        // Pelunasan selalu pas sesuai sisa tagihan, sama seperti di halaman klien
                        let nominal = this.jenis === 'pelunasan' ? this.sisa : 0;
                        this.jumlahRaw = nominal ? String(nominal) : '';
                        this.jumlahFormatted = nominal ? new Intl.NumberFormat('id-ID').format(nominal) : '';
                    },
                    formatRupiah(e) {
                        let angka = e.target.value.replace(/[^0-9]/g, '');
                        this.jumlahRaw = angka;
                        this.jumlahFormatted = angka ? new Intl.NumberFormat('id-ID').format(angka) : '';
                    }
                 }">
                <div class="card-h"><span class="card-t">Pembayaran</span>
                    <button type="button" class="pill-link" @click="open = !open">+ Catat</button>
                </div>
                <div class="card-b" x-show="open" x-cloak>
                    <form method="POST" action="{{ route('admin.pembayaran.store', $pemesanan) }}" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- DIJADIKAN 3 KOLOM AGAR SEIMBANG --}}
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:0 14px;">
                            <div class="field"><label>Jenis</label>
                                <select class="input" name="jenis" x-model="jenis" @change="autoFillJumlah()" required>
                                    <option value="" disabled selected>Pilih Jenis...</option>
                                    <option value="dp">DP</option>
                                    <option value="pelunasan">Pelunasan</option>
                                    <option value="cicilan">Cicilan</option>
                                </select>
                            </div>
                            <div class="field"><label>Jumlah (Rp)</label>
                                {{-- HIDDEN INPUT: nilai mentah tanpa titik, ini yang dikirim ke server --}}
                                <input type="hidden" name="jumlah" :value="jumlahRaw">
                                {{-- VISIBLE INPUT: yang dilihat admin, otomatis bertitik seperti di halaman klien --}}
                                <input class="input" type="text" inputmode="numeric" :value="jumlahFormatted"
                                    @input="formatRupiah" placeholder="Maks: {{ $pemesanan->sisa_format }}" required>
                            </div>
                            <div class="field"><label>Metode</label><input class="input" name="metode" placeholder="Transfer BCA"></div>
                        </div>

                        <div class="field"><label>Bukti (opsional)</label><input class="input" type="file" name="bukti" accept=".jpg,.jpeg,.png,.pdf"></div>
                        <button type="submit" class="btn btn-gold btn-full">Simpan Pembayaran</button>
                    </form>
                </div>
                <div class="tbl-wrap">
                    <table class="rtable">
                        <thead><tr><th>Waktu Tgl</th><th>Jenis</th><th>Jumlah</th><th>Status</th><th>Bukti</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @forelse ($pemesanan->pembayarans as $bayar)
                                <tr>
                                    <td data-label="Tgl">
                                        {{ $bayar->tanggal_bayar->translatedFormat('d M Y') }}<br>
                                        <span style="font-size:11px;color:var(--muted);">{{ $bayar->tanggal_bayar->translatedFormat('H:i:s') }}</span>
                                    </td>
                                    <td data-label="Jenis">{{ $bayar->jenis_label }}</td>
                                    <td data-label="Jumlah"><strong>Rp {{ number_format((float) $bayar->jumlah, 0, ',', '.') }}</strong></td>
                                    <td data-label="Status"><span class="badge {{ $bayar->status === 'terverifikasi' ? 'b-green' : ($bayar->status === 'ditolak' ? 'b-red' : 'b-orange') }}">{{ $bayar->status_label }}</span></td>
                                    <td data-label="Bukti">
                                        @if ($bayar->bukti)
                                            <a class="pill-link" href="{{ Storage::url($bayar->bukti) }}" target="_blank" style="font-weight:600; color:var(--gold2);">Lihat</a>
                                        @else
                                            <span class="muted">—</span>
                                        @endif
                                    </td>
                                    <td data-label="Aksi" class="cell-actions">
                                        <div class="flex-gap">
                                            @if ($bayar->status === 'menunggu')
                                                <form method="POST" action="{{ route('admin.pembayaran.verify', $bayar) }}" onsubmit="return confirm('Apakah Anda yakin ingin MENGESAHKAN pembayaran sebesar Rp {{ number_format((float) $bayar->jumlah, 0, ',', '.') }} ini?')">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="terverifikasi">
                                                    <button class="pill-link" style="color:var(--green); font-weight:600;">Sahkan</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.pembayaran.verify', $bayar) }}" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK pembayaran sebesar Rp {{ number_format((float) $bayar->jumlah, 0, ',', '.') }} ini?')">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button class="pill-link" style="color:var(--red); font-weight:600;">Tolak</button>
                                                </form>
                                            @else
                                                <span class="muted">—</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="muted" style="text-align:center;">Belum ada pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-h"><span class="card-t">Kirim WhatsApp</span></div>
                <div class="card-b">
                    <div class="flex-gap flex-wrap mb-2">
                        @foreach (['reminder_pembayaran'=>'Reminder Bayar','reminder_h3'=>'Reminder H-3','reminder_h1'=>'Reminder H-1'] as $jenis=>$lbl)
                            <form method="POST" action="{{ route('admin.pemesanan.wa', $pemesanan) }}">
                                @csrf<input type="hidden" name="jenis" value="{{ $jenis }}">
                                <button type="submit" class="btn btn-wa btn-sm">{{ $lbl }}</button>
                            </form>
                        @endforeach
                    </div>
                    <form method="POST" action="{{ route('admin.pemesanan.wa', $pemesanan) }}" style="margin-top:10px;">
                        @csrf<input type="hidden" name="jenis" value="manual">
                        <div class="field"><label>Pesan Manual</label><textarea class="input" name="pesan" placeholder="Tulis pesan WhatsApp..." required></textarea></div>
                        <button type="submit" class="btn btn-outline btn-sm">Kirim Pesan</button>
                    </form>

                    @if ($pemesanan->notifikasiLogs->isNotEmpty())
                        <div class="divider-sm"></div>
                        <p class="muted" style="font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Riwayat Kirim</p>
                        @foreach ($pemesanan->notifikasiLogs()->latest()->take(5)->get() as $log)
                            @php [$bc,$bt] = $waBadge($log->status); @endphp
                            <div style="font-size:12px;color:var(--ink4);padding:6px 0;border-bottom:1px dashed var(--border);display:flex;justify-content:space-between;gap:8px;align-items:center;">
                                <span>{{ ucwords(str_replace('_',' ',$log->jenis)) }} · {{ $log->created_at->translatedFormat('d M H:i') }}</span>
                                <span class="badge {{ $bc }}" style="font-size:10px;">{{ $bt }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection