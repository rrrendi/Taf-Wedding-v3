@extends('layouts.admin')

@section('title', 'Pemesanan — Taf Wedding')

@section('content')

    {{-- CSS GRID KHUSUS UNTUK HEADER & PENCARIAN (ANTI-BUG KEYBOARD MOBILE) --}}
    <style>
        .top-area {
            display: grid;
            gap: 12px;
            margin-bottom: 18px;
            align-items: center;

            /* DEFAULT MOBILE: Baris 1(Header, Kaca Pembesar), Baris 2(Filter Full) */
            grid-template-areas:
                "header sbtn"
                "filter filter";
            grid-template-columns: 1fr auto;
        }

        .ta-header {
            grid-area: header;
        }

        .ta-filter {
            grid-area: filter;
        }

        .ta-sbtn {
            grid-area: sbtn;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .ta-sinput {
            grid-area: sinput;
            display: none;
        }

        /* MOBILE - SAAT KACA PEMBESAR DIKLIK (Pencarian melebar penuhi baris) */
        .top-area.is-searching {
            grid-template-areas: "sinput";
            grid-template-columns: 1fr;
        }

        .top-area.is-searching .ta-header,
        .top-area.is-searching .ta-filter,
        .top-area.is-searching .ta-sbtn {
            display: none;
        }

        .top-area.is-searching .ta-sinput {
            display: block;
            animation: slideIn 0.2s ease-out forwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* DESKTOP - SEMUA SEBARIS (Kiri: Header | Tengah: Filter | Kanan: Input) */
        @media (min-width: 768px) {

            .top-area,
            .top-area.is-searching {
                grid-template-areas: "header filter sinput";
                grid-template-columns: 1fr auto 280px;
            }

            .ta-header,
            .ta-filter,
            .ta-sinput {
                display: block !important;
            }

            .ta-sbtn,
            .ta-close-btn {
                display: none !important;
            }
        }
    </style>

    @php
        $rows = $pemesanans->map(fn($b) => [
            'kode' => $b->kode,
            'nama' => $b->nama_klien,
            'tanggal' => $b->tanggal_acara->translatedFormat('d M Y'),
            'status' => $b->status,
            'status_label' => $b->status_label,
            'bayar' => $b->status_bayar,
            'bayar_label' => $b->status_bayar_label,
            'url' => route('admin.pemesanan.show', $b),
            'cari' => mb_strtolower($b->nama_klien . ' ' . $b->kode),
        ])->values();
    @endphp
    <div class="fade" x-data="orderList({{ Illuminate\Support\Js::from($rows) }})">

        {{-- AREA HEADER CERDAS MENGGUNAKAN CSS GRID --}}
        <div class="top-area" :class="{ 'is-searching': mobileSearch }">

            {{-- KIRI: Teks Header --}}
            <div class="ta-header">
                <h1
                    style="font-family:var(--serif); font-weight:600; font-size:29px; color:var(--ink); line-height:1.15; margin-bottom:4px;">
                    Pemesanan</h1>
                <p style="font-size:13.5px; color:var(--ink4); margin:0;">Kelola seluruh pesanan klien</p>
            </div>

            {{-- TENGAH: Filter Status --}}
            <div class="ta-filter">
                <select class="input" x-model="status" @change="page = 1" style="width: 100%; 
               padding: 0 44px 0 16px; 
               height: 42px; 
               border-radius: 999px; 
               box-shadow: var(--sh1); 
               font-weight: 600; 
               cursor: pointer; 
               border: 1.5px solid var(--border2); 
               appearance: none; 
               -webkit-appearance: none; 
               background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22 viewBox%3D%220 0 24 24%22 width%3D%2218%22 height%3D%2218%22 fill%3D%22none%22 stroke%3D%22%23564A37%22 stroke-width%3D%222.5%22 stroke-linecap%3D%22round%22 stroke-linejoin%3D%22round%22%3E%3Cpath d%3D%22m6 9 6 6 6-6%22%2F%3E%3C%2Fsvg%3E'); 
               background-repeat: no-repeat; 
               background-position: right 14px center;">
                    <option value="">Semua Status</option>
                    <option value="pending">Menunggu Konfirmasi</option>
                    <option value="dikonfirmasi">Dikonfirmasi</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
            </div>

            {{-- KANAN: Tombol Kaca Pembesar Mobile --}}
            <button type="button" class="ta-sbtn btn btn-gold" @click="mobileSearch = true"
                style="padding: 0; border-radius: 50%; width: 42px; height: 42px; box-shadow: var(--sh1);">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m21 21-4-4" />
                </svg>
            </button>

            {{-- KANAN: Input Pencarian --}}
            <div class="ta-sinput" style="position: relative; width: 100%;">
                <span
                    style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; display: flex;">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m21 21-4-4" />
                    </svg>
                </span>

                <input class="input"
                    style="width: 100%; border-radius: 999px; padding: 0 40px; height: 42px; box-shadow: var(--sh1); border: 1.5px solid var(--border2);"
                    placeholder="Cari pesanan..." autocomplete="off" x-model="q" @input="page = 1">

                {{-- Tombol Tutup Khusus Mobile (Silang) --}}
                <button type="button" class="ta-close-btn" @click="mobileSearch = false; q = ''; page = 1"
                    style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--muted); cursor: pointer; padding: 4px; display: flex;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Info Total Hasil Pencarian --}}
        <div class="flex-between" style="margin-bottom:14px; border-bottom: 1px solid var(--border); padding-bottom: 10px;">
            <span class="card-t" x-text="(q || status) ? 'Hasil Pencarian' : 'Semua Pesanan'"></span>
            <span class="muted" style="font-size:12.5px; font-weight:600;"><span x-text="filtered.length"></span>
                pesanan</span>
        </div>

        {{-- Daftar kartu pesanan --}}
        <div class="ogrid">
            <template x-for="o in paged" :key="o.kode">
                <a :href="o.url"
                    style="display:block;background:var(--card);border:1.5px solid var(--border);border-radius:var(--r2);box-shadow:var(--sh1);padding:15px 16px;transition:transform 0.2s, box-shadow 0.2s;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--sh2)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--sh1)'">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:8px;">
                        <span style="font-size:11px;font-weight:800;letter-spacing:1px;color:var(--goldDeep);"
                            x-text="o.kode"></span>
                        <span class="badge" :class="stCls(o.status)" x-text="o.status_label"></span>
                    </div>
                    <div style="font-family:var(--serif);font-size:21px;font-weight:600;color:var(--ink);line-height:1.2;margin-bottom:3px;"
                        x-text="o.nama"></div>
                    <div style="font-size:13px;color:var(--ink3);" x-text="o.tanggal"></div>

                    <div
                        style="display:flex;justify-content:space-between;align-items:center;margin-top:12px;padding-top:12px;border-top:1px dashed var(--border);">
                        <span class="badge" :class="byCls(o.bayar)" x-text="o.bayar_label"></span>
                        <span
                            style="font-weight:700;color:var(--gold2);font-size:13px;display:flex;align-items:center;gap:4px;">Kelola
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round">
                                <path d="m9 18 6-6-6-6" />
                            </svg></span>
                    </div>
                </a>
            </template>
        </div>

        {{-- Data Kosong --}}
        <div x-show="filtered.length === 0" x-cloak class="card" style="margin-top: 20px;">
            <div class="empty">
                <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                        stroke-linecap="round">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m21 21-4-4" />
                    </svg></div>
                <p>Tidak ada pesanan yang cocok dengan pencarian.</p>
            </div>
        </div>

        {{-- Pagination (maks 10/hal) --}}
        <div class="pager" x-show="pages > 1" x-cloak>
            <button class="pg-btn" @click="go(page - 1)" :disabled="page === 1">&larr;</button>
            <template x-for="p in pages" :key="p">
                <button class="pg-btn" :class="{ 'on': p === page }" @click="go(p)" x-text="p"></button>
            </template>
            <button class="pg-btn" @click="go(page + 1)" :disabled="page === pages">&rarr;</button>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function orderList(rows) {
            return {
                rows: rows,
                q: '',
                status: '',
                page: 1,
                per: 10,
                mobileSearch: false, // State kontrol pencarian mobile kebal bug
                get filtered() {
                    let r = this.rows;
                    if (this.status) r = r.filter(x => x.status === this.status);
                    const k = this.q.trim().toLowerCase();
                    if (k) r = r.filter(x => x.cari.includes(k));
                    return r;
                },
                get pages() { return Math.max(1, Math.ceil(this.filtered.length / this.per)); },
                get paged() {
                    if (this.page > this.pages) this.page = this.pages;
                    const start = (this.page - 1) * this.per;
                    return this.filtered.slice(start, start + this.per);
                },
                go(p) { this.page = Math.min(Math.max(1, p), this.pages); },
                stCls(s) {
                    return s === 'dikonfirmasi' ? 'b-green' : (s === 'dibatalkan' ? 'b-red' : (s === 'selesai' ? 'b-blue' : 'b-orange'));
                },
                byCls(s) {
                    return s === 'lunas' ? 'b-green' : (s === 'dp' ? 'b-blue' : 'b-red');
                },
            };
        }
    </script>
@endpush