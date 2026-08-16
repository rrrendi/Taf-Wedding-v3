@extends('layouts.admin')

@section('title', 'Notifikasi WhatsApp — Taf Wedding')

@section('content')
    @php
        $hariArr = collect(explode(',', $cfg['hari']))->map(fn($x) => (int) trim($x))->filter()->values();
        $waBadge = fn($s) => $s === 'terkirim' ? ['b-green', 'Terkirim'] : ($s === 'gagal' ? ['b-red', 'Gagal'] : ['b-orange', 'Mode Demo']);
    @endphp
    <div class="fade">
        <div class="pg-head">
            <div>
                <h1>Notifikasi WhatsApp</h1>
                <p>Atur kapan pengingat otomatis dikirim ke klien</p>
            </div>
        </div>

        {{-- PENGATURAN (satu card, agar tombol Simpan jelas berlaku untuk semua pengaturan di atasnya) --}}
        <form method="POST" action="{{ route('admin.notifikasi.update') }}" x-data="{
                aktif: {{ $cfg['aktif'] ? 'true' : 'false' }},
                bayar: {{ $cfg['bayar_aktif'] ? 'true' : 'false' }},
                days: {{ \Illuminate\Support\Js::from($hariArr) }},
                options: [1,3,7,14,30],
                custom: '',
                toggle(d){ this.days = this.days.includes(d) ? this.days.filter(x=>x!==d) : [...this.days, d] ; this.days.sort((a,b)=>b-a) },
                addCustom(){ let n = parseInt(this.custom); if(n>0 && !this.days.includes(n)){ this.days.push(n); this.days.sort((a,b)=>b-a) } this.custom='' },
                get csv(){ return this.days.join(',') }
              }">
            @csrf

            <div class="card">
                <div class="card-h"><span class="card-t">Pengaturan Notifikasi WhatsApp</span></div>
                <div class="card-b">

                    <label
                        style="display:flex;justify-content:space-between;align-items:center;gap:12px;cursor:pointer;padding:6px 0;">
                        <span>
                            <span style="font-weight:700;color:var(--ink);">Aktifkan pengiriman WhatsApp</span>
                            <span style="display:block;font-size:12.5px;color:var(--muted);margin-top:2px;">Matikan
                                sementara bila nomor sedang bermasalah — pesan tetap dicatat di log tanpa benar-benar
                                dikirim.</span>
                        </span>
                        <input type="checkbox" name="fonnte_enabled" value="1" {{ $cfg['gw_aktif'] ? 'checked' : '' }}
                            style="width:20px;height:20px;flex:none;accent-color:var(--gold);">
                    </label>

                    <div class="field" style="margin-top:10px;">
                        <label>Nomor WhatsApp Admin/Owner</label>
                        <input type="text" name="fonnte_admin_number" class="input"
                            value="{{ old('fonnte_admin_number', $cfg['gw_admin_number']) }}"
                            placeholder="mis. 08579xxxxxxx">
                        <p class="muted" style="font-size:12px;margin-top:6px;">Nomor ini menerima notifikasi tiap ada
                            pesanan baru.</p>
                    </div>

                    <div class="field">
                        <label>Token Fonnte</label>
                        <input type="password" name="fonnte_token" class="input"
                            placeholder="{{ $cfg['gw_token_set'] ? '•••••••• (tersimpan — kosongkan bila tidak ingin mengganti)' : 'Belum diatur — isi token dari dashboard Fonnte' }}">
                        <p class="muted" style="font-size:12px;margin-top:6px;">
                            @if ($cfg['gw_token_set'])
                                <span style="color:var(--green);font-weight:700;">✓ Token tersimpan.</span>
                            @else
                                <span style="color:var(--red);font-weight:700;">⚠ Token belum diatur — notifikasi berjalan
                                    mode
                                    simulasi.</span>
                            @endif
                        </p>
                    </div>

                    <div class="divider"></div>

                    {{-- master toggle --}}
                    <label
                        style="display:flex;justify-content:space-between;align-items:center;gap:12px;cursor:pointer;padding:6px 0;">
                        <span>
                            <span style="font-weight:700;color:var(--ink);">Aktifkan reminder otomatis</span>
                            <span style="display:block;font-size:12.5px;color:var(--muted);margin-top:2px;">Sistem
                                mengirim
                                pengingat sendiri sesuai jadwal di bawah.</span>
                        </span>
                        <input type="checkbox" name="reminder_aktif" value="1" x-model="aktif"
                            style="width:20px;height:20px;flex:none;accent-color:var(--gold);">
                    </label>

                    <div x-show="aktif" x-cloak style="margin-top:10px;">
                        {{-- H-minus chips --}}
                        <div class="field">
                            <label>Kirim pengingat acara pada</label>
                            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;">
                                <template x-for="d in options" :key="d">
                                    <button type="button" @click="toggle(d)" :style="days.includes(d)
                                            ? 'background:var(--gold-grad);color:#2A1F08;border-color:transparent;'
                                            : 'background:#fff;color:var(--ink3);border-color:var(--border2);'"
                                        style="padding:8px 15px;border-radius:999px;border:1.5px solid;font-weight:700;font-size:13px;"
                                        x-text="'H-'+d"></button>
                                </template>
                            </div>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <input type="number" min="1" max="90" x-model="custom" class="input"
                                    style="max-width:120px;" placeholder="H- lain">
                                <button type="button" class="btn btn-outline btn-sm" @click="addCustom()">Tambah</button>
                            </div>
                            <p class="muted" style="font-size:12px;margin-top:8px;">
                                Terpilih: <strong
                                    x-text="days.length ? days.map(d=>'H-'+d).join(', ') : 'belum ada'"></strong>.
                                Contoh: H-7, H-3, H-1 berarti klien diingatkan 7 hari, 3 hari, dan 1 hari sebelum acara.
                            </p>
                            <input type="hidden" name="reminder_hari_h" :value="csv">
                        </div>

                        {{-- jam --}}
                        <div class="field">
                            <label>Jam pengiriman setiap hari</label>
                            <select class="input" name="reminder_jam" style="max-width:200px;">
                                @for ($h = 0; $h < 24; $h++)
                                    <option value="{{ $h }}" @selected($cfg['jam'] === $h)>{{ sprintf('%02d:00', $h) }} WIB
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="divider-sm"></div>

                    {{-- payment reminder --}}
                    <label
                        style="display:flex;justify-content:space-between;align-items:center;gap:12px;cursor:pointer;padding:6px 0;">
                        <span>
                            <span style="font-weight:700;color:var(--ink);">Reminder pembayaran</span>
                            <span style="display:block;font-size:12.5px;color:var(--muted);margin-top:2px;">Ingatkan
                                klien
                                yang masih punya sisa tagihan menjelang acara.</span>
                        </span>
                        <input type="checkbox" name="reminder_bayar_aktif" value="1" x-model="bayar"
                            style="width:20px;height:20px;flex:none;accent-color:var(--gold);">
                    </label>
                    <div class="field" x-show="bayar" x-cloak style="margin-top:10px;">
                        <label>Untuk acara dalam (hari) ke depan</label>
                        <input type="number" name="reminder_bayar_dalam" min="1" max="90"
                            value="{{ $cfg['bayar_dalam'] }}" class="input" style="max-width:160px;">
                    </div>

                    <div class="divider"></div>

                    <button type="submit" class="btn btn-gold btn-full">Simpan Pengaturan</button>
                    <p class="muted" style="font-size:12px;margin-top:12px;line-height:1.6;">
                        <strong>Catatan:</strong> Agar pengingat terkirim otomatis, server harus menjalankan penjadwal
                        Laravel
                        (<code>php artisan schedule:work</code> saat demo, atau cron <code>schedule:run</code> di
                        server).
                        Anda juga selalu
                        bisa mengirim manual dari halaman detail tiap pesanan.
                    </p>
                </div>
            </div>
        </form>

        {{-- RIWAYAT --}}
        <div class="card">
            <div class="card-h"><span class="card-t">Riwayat Pesan</span><span class="muted" style="font-size:12px;">20
                    terakhir</span></div>
            <div class="card-b" style="padding-bottom:6px;">
                <p class="muted" style="font-size:12.5px;margin-bottom:4px;">
                    <span class="badge b-green" style="font-size:10px;">Terkirim</span> berhasil dikirim ·
                    <span class="badge b-red" style="font-size:10px;">Gagal</span> gagal dikirim ·
                    <span class="badge b-orange" style="font-size:10px;">Mode Demo</span> dicatat tapi belum dikirim
                    (mode
                    demo).
                </p>
            </div>
            <div class="tbl-wrap">
                <table class="rtable">
                    <thead>
                        <tr>
                            <th>Klien</th>
                            <th>Jenis</th>
                            <th>Tujuan</th>
                            <th>Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            @php [$bc, $bt] = $waBadge($log->status); @endphp
                            <tr>
                                <td data-label="Klien">
                                    <strong>{{ $log->pemesanan?->nama_klien ?? '—' }}</strong>
                                    <span class="badge {{ $bc }} rt-status-preview">{{ $bt }}</span>
                                </td>
                                <td data-label="Jenis">{{ ucwords(str_replace('_', ' ', $log->jenis)) }}</td>
                                <td data-label="Tujuan" style="font-size:12.5px;color:var(--muted);">{{ $log->tujuan }}
                                </td>
                                <td data-label="Waktu" style="font-size:12.5px;">
                                    {{ $log->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td data-label="Status"><span class="badge {{ $bc }}">{{ $bt }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="muted" style="text-align:center;padding:26px;">Belum ada pesan
                                    terkirim.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection