@extends('layouts.public')

@section('title', 'Form Pemesanan — Taf Wedding')

@push('head')
    <style>
        .jenis-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            margin-top: 4px;
        }

        @media (min-width:640px) {
            .jenis-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .jenis-card {
            position: relative;
            background: var(--card);
            border: 1.5px solid var(--border2);
            border-radius: var(--r2);
            padding: 20px 18px;
            cursor: pointer;
            transition: .15s;
            box-shadow: var(--sh1);
        }

        .jenis-card:hover {
            border-color: var(--gold);
            transform: translateY(-2px);
            box-shadow: var(--sh2);
        }

        .jenis-card.on {
            border-color: var(--gold);
            background: linear-gradient(180deg, #FFFDF7, #FBF3DE);
        }

        .jenis-ico {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: var(--goldBg);
            color: var(--gold2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .jenis-title {
            font-family: var(--serif);
            font-size: 19px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 5px;
        }

        .jenis-desc {
            font-size: 12.5px;
            color: var(--ink3);
            line-height: 1.55;
        }
    </style>
@endpush

@section('content')
    @php
        $layananJson = $layanans->map(fn($l) => [
            'id' => $l->id,
            'nama' => $l->nama,
            'harga' => (float) $l->harga,
            'deskripsi' => $l->deskripsi,
            'gambar_url' => $l->gambar_url,
            'kategori' => $l->kategori,
        ])->values();
    @endphp

    <div x-data="bookingWizard({{ Illuminate\Support\Js::from($layananJson) }}, '{{ url('/cek-tanggal') }}')" x-cloak>
        @include('client.partials.topbar', ['current' => 'Buat Pesanan Baru'])
        <div class="cl-page fade" style="padding-top:24px;padding-bottom:40px;">

            <div
                style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
                <div>
                    <h1 style="font-family:var(--serif);font-size:clamp(24px,4vw,28px);color:var(--ink);">Buat Pesanan Baru
                    </h1>
                    <p style="font-size:13.5px;color:var(--goldDeep);margin-top:6px;">Ikuti 4 langkah singkat — total biaya
                        terlihat berjalan di setiap tahap.</p>
                </div>
                <a href="{{ route('client.pemesanan.index') }}"
                    style="font-size:13px;font-weight:700;color:var(--ink3);text-decoration:none;">Batal &amp; Kembali</a>
            </div>

            {{-- STEPPER 4 LANGKAH --}}
            <div class="stepper3">
                @foreach (['Jenis Acara', 'Data & Acara', 'Pilih Layanan', 'Konfirmasi'] as $i => $label)
                    @php $n = $i + 1; @endphp
                    <div class="step-item">
                        <div class="step-node" :class="{ done: step > {{ $n }}, active: step === {{ $n }} }"
                            @click="goStep({{ $n }})">
                            <span class="step-circle">
                                <template x-if="step > {{ $n }}"><svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="3">
                                        <path d="M20 6 9 17l-5-5" />
                                    </svg></template>
                                <template x-if="step <= {{ $n }}"><span>{{ $n }}</span></template>
                            </span>
                            <span class="step-label">{{ $label }}</span>
                        </div>
                    </div>
                    @if (!$loop->last)
                    <div class="step-line" :class="{ done: step > {{ $n }} }"></div>@endif
                @endforeach
            </div>

            @if ($errors->any())
                <div class="alert alert-error" style="margin-bottom:20px;"><span>{{ $errors->first() }}</span></div>
            @endif

            <form method="POST" action="{{ route('client.pemesanan.store') }}" id="pemesananForm" @submit="syncBeforeSubmit($event)">
                @csrf
                <input type="hidden" name="jenis_acara" :value="jenisAcara">

                <div class="wiz-layout">
                    <div>

                        {{-- STEP 1: JENIS ACARA (Wedding / Non-Wedding) --}}
                        <div class="wiz-panel" x-show="step === 1">
                            <div class="wiz-panel-title">Acara Anda Wedding atau Non-Wedding?</div>
                            <p class="wiz-panel-desc">Pilih salah satu supaya kami tahu form &amp; layanan yang paling
                                sesuai. Acara non-wedding tetap bisa memesan layanan Makeup Only &amp; Tambahan.</p>

                            <div class="jenis-grid">
                                <div class="jenis-card" :class="{ on: jenisAcara === 'wedding' }"
                                    @click="jenisAcara = 'wedding'; filter = 'semua'">
                                    <div class="jenis-ico">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path d="M12 3c-1.5 2-2 3.4-2 4.6A2 2 0 0 0 12 9.6a2 2 0 0 0 2-2C14 6.4 13.5 5 12 3Z" />
                                            <circle cx="7" cy="17" r="4" />
                                            <circle cx="17" cy="17" r="4" />
                                            <path d="M9.5 14.5 12 9.6l2.5 4.9" />
                                        </svg>
                                    </div>
                                    <div class="jenis-title">Wedding</div>
                                    <div class="jenis-desc">Pernikahan / prewedding — paket lengkap makeup, dekorasi,
                                        dokumentasi, hingga catering.</div>
                                </div>
                                <div class="jenis-card" :class="{ on: jenisAcara === 'lainnya' }"
                                    @click="jenisAcara = 'lainnya'; filter = 'semua'">
                                    <div class="jenis-ico">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <circle cx="12" cy="12" r="9" />
                                            <path d="M12 7v5l3.5 2" />
                                        </svg>
                                    </div>
                                    <div class="jenis-title">Non-Wedding / Acara Lain</div>
                                    <div class="jenis-desc">Ulang tahun, wisuda, photoshoot, atau acara lainnya — pesan
                                        layanan Makeup Only &amp; Tambahan sesuai kebutuhan.</div>
                                </div>
                            </div>

                            <div class="wiz-nav">
                                <span></span>
                                <button type="button" class="btn btn-gold" @click="next">Lanjut</button>
                            </div>
                        </div>

                        {{-- STEP 2: DATA & ACARA (digabung, menyesuaikan jenis acara) --}}
                        <div class="wiz-panel" x-show="step === 2">
                            <div class="wiz-panel-title"
                                x-text="jenisAcara === 'wedding' ? 'Data Mempelai & Acara' : 'Data Pemesan & Acara'"></div>
                            <p class="wiz-panel-desc">Informasi ini digunakan tim kami untuk verifikasi dan koordinasi
                                jadwal.</p>

                            <template x-if="jenisAcara === 'wedding'">
                                <div class="row">
                                    <div class="field">
                                        <label>Nama Mempelai Pria</label>
                                        <input class="input" name="nama_pria" x-model="nama_pria"
                                            placeholder="Contoh: Ahmad Rizky" required>
                                    </div>
                                    <div class="field">
                                        <label>Nama Mempelai Wanita</label>
                                        <input class="input" name="nama_wanita" x-model="nama_wanita"
                                            placeholder="Contoh: Rina Pratiwi" required>
                                    </div>
                                </div>
                            </template>
                            <template x-if="jenisAcara !== 'wedding'">
                                <div class="row">
                                    <div class="field">
                                        <label>Nama Pemesan</label>
                                        <input class="input" name="nama_pria" x-model="nama_pria"
                                            placeholder="Contoh: Sinta Wulandari" required>
                                    </div>
                                    <div class="field">
                                        <label>Jenis / Nama Acara</label>
                                        <input class="input" name="nama_acara" x-model="namaAcara" list="jenisAcaraOpsi"
                                            placeholder="Contoh: Ulang Tahun, Wisuda, Photoshoot" required>
                                        <datalist id="jenisAcaraOpsi">
                                            <option value="Ulang Tahun">
                                            <option value="Wisuda / Graduation">
                                            <option value="Prewedding / Photoshoot">
                                            <option value="Anniversary">
                                            <option value="Acara Kantor / Korporat">
                                        </datalist>
                                    </div>
                                </div>
                            </template>
                            <div class="row">
                                <div class="field">
                                    <label>No. WhatsApp Aktif</label>
                                    <input class="input" name="phone" x-model="phone" placeholder="08xxxxxxxxxx" required>
                                </div>
                                <div class="field">
                                    <label>Email</label>
                                    <input class="input" type="email" name="email" x-model="email"
                                        placeholder="email@anda.com">
                                </div>
                            </div>
                            <div class="row">
                                <div class="field">
                                    <label>Tanggal Acara</label>
                                    <input class="input" type="date" name="tanggal_acara" x-model="tanggal"
                                        @change="cekTanggal" min="{{ date('Y-m-d') }}" required>
                                    <p class="muted" style="font-size:11.5px;margin-top:6px;">Sistem mengecek ketersediaan
                                        tanggal otomatis.</p>
                                    <p x-show="tanggalTerisi" x-cloak
                                        style="color:var(--orange);font-size:12px;margin-top:6px;font-weight:600;">⚠ Tanggal
                                        ini cukup diminati — admin akan mengonfirmasi ketersediaan tim.</p>
                                </div>
                                <div class="field">
                                    <label>Estimasi Jumlah Tamu</label>
                                    <select class="input" name="jumlah_tamu" x-model="jumlah_tamu">
                                        <option value="" disabled>Pilih perkiraan jumlah tamu</option>
                                        @foreach (['≤ 100 orang', '100 – 300 orang', '300 – 500 orang', '> 500 orang'] as $opt)
                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="field" style="margin-bottom:0;">
                                <label>Lokasi / Gedung Venue</label>
                                <input class="input" name="lokasi" x-model="lokasi"
                                    placeholder="Contoh: Gedung Sate, Bandung / alamat lengkap rumah" required>
                            </div>

                            <div class="wiz-nav">
                                <button type="button" class="btn btn-outline" @click="prev">Kembali</button>
                                <button type="button" class="btn btn-gold" @click="next">Lanjut Pilih Layanan</button>
                            </div>
                        </div>

                        {{-- STEP 3: PILIH LAYANAN (kartu bergambar) --}}
                        <div class="wiz-panel" x-show="step === 3">
                            <div class="wiz-panel-title">Pilih Layanan</div>
                            <p class="wiz-panel-desc">Ketuk kartu untuk menambahkan. Ketuk ikon <strong
                                    style="color:var(--goldDeep)">(i)</strong> untuk detail paket.
                                <template x-if="jenisAcara === 'lainnya'"><span> Khusus acara non-wedding, hanya layanan
                                        Makeup Only &amp; Tambahan yang ditampilkan.</span></template>
                            </p>

                            <div class="chips" style="margin-bottom:16px;">
                                <button type="button" class="chip" :class="{ active: filter === 'semua' }"
                                    @click="filter = 'semua'">Semua</button>
                                <button type="button" class="chip" x-show="jenisAcara !== 'lainnya'"
                                    :class="{ active: filter === 'paket_wedding' }"
                                    @click="filter = 'paket_wedding'">Paket Wedding</button>
                                <button type="button" class="chip" :class="{ active: filter === 'makeup_only' }"
                                    @click="filter = 'makeup_only'">Makeup Only</button>
                                <button type="button" class="chip" :class="{ active: filter === 'tambahan' }"
                                    @click="filter = 'tambahan'">Tambahan</button>
                            </div>

                            <div class="svc-grid">
                                <template x-for="l in filteredLayanan()" :key="l.id">
                                    <div class="svc-card" :class="{ on: isSelected(l.id) }" @click="toggle(l.id)">
                                        <div class="svc-photo"
                                            :style="l.gambar_url ? 'background-image:url(' + l.gambar_url + ')' : ''">
                                            <template x-if="!l.gambar_url">
                                                <div class="svc-photo-empty">
                                                    <svg viewBox="0 0 24 24" width="34" height="34" fill="none"
                                                        stroke="rgba(231,200,121,.3)" stroke-width="1.6">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                                        <polyline points="21 15 16 10 5 21" />
                                                    </svg>
                                                </div>
                                            </template>
                                            <div class="svc-photo-overlay"></div>
                                            <template x-if="isSelected(l.id)"><span
                                                    class="svc-tag-on">Dipilih</span></template>
                                            <button type="button" class="svc-info-btn" @click.stop="openInfoModal(l)"
                                                title="Detail layanan">
                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                    stroke="currentColor" stroke-width="2.6">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                                </svg>
                                            </button>
                                            <div class="svc-name-on-photo" x-text="l.nama"></div>
                                        </div>
                                        <div class="svc-body">
                                            <span class="svc-kategori-tag" x-text="kategoriLabel(l.kategori)"></span>
                                            <div class="svc-desc" x-text="l.deskripsi || 'Belum ada deskripsi.'"></div>
                                            <div class="svc-foot">
                                                <span class="svc-price" x-text="fmt(l.harga)"></span>
                                                <span class="svc-add"
                                                    x-text="isSelected(l.id) ? '✓ Dipilih' : '+ Pilih'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <template x-for="id in selected" :key="'h'+id">
                                <input type="hidden" name="layanan[]" :value="id">
                            </template>

                            <div class="wiz-nav">
                                <button type="button" class="btn btn-outline" @click="prev">Kembali</button>
                                <button type="button" class="btn btn-gold" @click="next">Lanjut Konfirmasi</button>
                            </div>
                        </div>

                        {{-- STEP 4: KONFIRMASI --}}
                        <div class="wiz-panel" x-show="step === 4">
                            <div class="wiz-panel-title">Konfirmasi Pesanan</div>
                            <p class="wiz-panel-desc">Periksa sekali lagi sebelum mengirim ke admin.</p>

                            <div class="wiz-review-block">
                                <div class="wiz-review-head">
                                    <span class="wiz-review-label"
                                        x-text="jenisAcara === 'wedding' ? 'Data Mempelai & Acara' : 'Data Pemesan & Acara'"></span>
                                    <button type="button" class="wiz-review-edit" @click="goStep(2)">Ubah</button>
                                </div>
                                <div class="wiz-review-row"><span class="k"
                                        x-text="jenisAcara === 'wedding' ? 'Mempelai' : 'Nama Pemesan'"></span><span
                                        class="v"
                                        x-text="jenisAcara === 'wedding' ? ((nama_pria || '—') + ' & ' + (nama_wanita || '—')) : (nama_pria || '—')"></span>
                                </div>
                                <template x-if="jenisAcara !== 'wedding'">
                                    <div class="wiz-review-row"><span class="k">Jenis Acara</span><span class="v"
                                            x-text="namaAcara || '—'"></span></div>
                                </template>
                                <div class="wiz-review-row"><span class="k">WhatsApp</span><span class="v"
                                        x-text="phone || '—'"></span></div>
                                <div class="wiz-review-row"><span class="k">Tanggal</span><span class="v"
                                        x-text="tanggal || '—'"></span></div>
                                <div class="wiz-review-row"><span class="k">Lokasi</span><span class="v"
                                        x-text="lokasi || '—'"></span></div>
                            </div>

                            <div class="wiz-review-block">
                                <div class="wiz-review-head">
                                    <span class="wiz-review-label">Layanan Dipilih</span>
                                    <button type="button" class="wiz-review-edit" @click="goStep(3)">Ubah</button>
                                </div>
                                <template x-for="l in selectedItems()" :key="'r'+l.id">
                                    <div class="wiz-review-row"><span class="k" x-text="l.nama"></span><span class="v"
                                            x-text="fmt(l.harga)"></span></div>
                                </template>
                            </div>

                            <div class="field" style="margin-bottom:0;">
                                <label>Catatan Khusus (Opsional)</label>
                                <textarea class="input" name="catatan"
                                    placeholder="Tema warna, jenis adat, atau kebutuhan khusus lainnya...">{{ old('catatan') }}</textarea>
                            </div>

                            <div class="wiz-nav">
                                <button type="button" class="btn btn-outline" @click="prev">Kembali</button>
                                <button type="submit" class="btn btn-gold" style="min-width:180px;">Kirim Pesanan</button>
                            </div>
                        </div>

                    </div>

                    {{-- RINGKASAN STICKY (desktop) --}}
                    <div class="wiz-rail" style="display:none;"
                        x-bind:style="window.innerWidth >= 1024 ? '' : 'display:none'">
                        <div class="wiz-ticket">
                            <div class="wiz-ticket-head">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path
                                        d="m20.59 13.41-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82Z" />
                                    <circle cx="7" cy="7" r="1" />
                                </svg>
                                <div class="wiz-ticket-title">Ringkasan Pesanan</div>
                            </div>
                            <div class="wiz-ticket-perf"></div>
                            <div class="wiz-ticket-body">
                                <template x-if="selected.length === 0">
                                    <div class="wiz-ticket-empty">Belum ada layanan dipilih</div>
                                </template>
                                <template x-for="l in selectedItems()" :key="'t'+l.id">
                                    <div class="wiz-ticket-item"><span class="n" x-text="l.nama"></span><span class="v"
                                            x-text="fmt(l.harga)"></span></div>
                                </template>
                            </div>
                            <div class="wiz-ticket-perf"></div>
                            <div class="wiz-ticket-total"><span class="lbl">Estimasi Total</span><span class="val"
                                    x-text="fmt(total())"></span></div>
                            <div class="wiz-ticket-note">*Estimasi awal. Total akhir dikonfirmasi admin setelah pengecekan
                                detail.</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- BAR RINGKASAN MOBILE --}}
        <div class="wiz-mbar" :class="{ open: mobileSummaryOpen }">
            <button type="button" class="wiz-mbar-toggle" @click="mobileSummaryOpen = !mobileSummaryOpen">
                <span x-text="selected.length + ' layanan dipilih'"></span>
                <span x-text="fmt(total())"></span>
            </button>
            <div class="wiz-mbar-detail">
                <template x-for="l in selectedItems()" :key="'m'+l.id">
                    <div class="wiz-ticket-item" style="padding:6px 18px;"><span class="n" x-text="l.nama"></span><span
                            class="v" x-text="fmt(l.harga)"></span></div>
                </template>
            </div>
            <div class="wiz-mbar-btns">
                <button type="button" class="btn btn-outline" @click="prev" x-show="step > 1">Kembali</button>
                <button type="button" class="btn btn-gold" @click="next" x-show="step < 4">Lanjutkan</button>
                <button type="submit" class="btn btn-gold" form="pemesananForm" x-show="step === 4">Kirim Pesanan</button>
            </div>
        </div>

        {{-- MODAL INFO LAYANAN --}}
        <template x-teleport="body">
            <div x-show="isModalOpen" style="display:none;">
                <div x-show="isModalOpen" x-transition.opacity
                    style="position:fixed;inset:0;z-index:999998;background:rgba(0,0,0,.7);backdrop-filter:blur(4px);"
                    @click="closeInfoModal()"></div>
                <div
                    style="position:fixed;inset:0;z-index:999999;display:flex;align-items:center;justify-content:center;pointer-events:none;padding:20px;">
                    <div x-show="isModalOpen" x-transition.opacity @click.stop
                        style="pointer-events:auto;background:#FFFFFF;border-radius:16px;box-shadow:0 20px 40px rgba(0,0,0,.5);width:100%;max-width:440px;overflow:hidden;display:flex;flex-direction:column;max-height:90vh;position:relative;">
                        <button type="button" @click="closeInfoModal()"
                            style="position:absolute;top:16px;right:16px;background:rgba(0,0,0,.5);border:none;width:34px;height:34px;border-radius:50%;color:#FFF;cursor:pointer;display:flex;align-items:center;justify-content:center;z-index:10;">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <line x1="18" y1="6" x2="6" y2="18" />
                                <line x1="6" y1="6" x2="18" y2="18" />
                            </svg>
                        </button>
                        <template x-if="activeModalData && activeModalData.gambar_url">
                            <div style="width:100%;height:240px;background:#f0f0f0;"><img :src="activeModalData.gambar_url"
                                    style="width:100%;height:100%;object-fit:cover;"></div>
                        </template>
                        <template x-if="activeModalData && !activeModalData.gambar_url">
                            <div
                                style="width:100%;height:120px;background:linear-gradient(145deg,#1C1710,#110E08);display:flex;align-items:center;justify-content:center;">
                                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="rgba(231,200,121,.2)"
                                    stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg>
                            </div>
                        </template>
                        <div style="padding:24px;overflow-y:auto;">
                            <h3 style="font-family:var(--serif);font-size:24px;color:var(--ink);margin:0 0 8px 0;"
                                x-text="activeModalData ? activeModalData.nama : ''"></h3>
                            <div style="color:var(--goldDeep);font-weight:800;font-size:16px;margin-bottom:20px;"
                                x-text="activeModalData ? fmt(activeModalData.harga) : ''"></div>
                            <p style="color:var(--ink3);font-size:14.5px;line-height:1.6;margin:0;white-space:pre-wrap;"
                                x-text="activeModalData && activeModalData.deskripsi ? activeModalData.deskripsi : 'Belum ada deskripsi detail untuk layanan ini.'">
                            </p>
                        </div>
                        <div
                            style="padding:16px 24px;border-top:1.5px solid var(--border);background:#FAFAFA;display:flex;justify-content:flex-end;">
                            <button type="button" @click="closeInfoModal()" class="btn btn-outline">Tutup Info</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

    </div>
@endsection

@push('scripts')
    <script>
        function bookingWizard(layanan, cekUrl) {
            return {
                layanan: layanan,
                selected: @json(collect(old('layanan', []))->map(fn($v) => (int) $v)->values()),
                tanggal: '{{ old('tanggal_acara') }}',
                tanggalTerisi: false,
                cekUrl: cekUrl,
                isModalOpen: false,
                activeModalData: null,
                filter: 'semua',

                isSelected(id) { return this.selected.includes(id); },
                toggle(id) {
                    this.selected = this.isSelected(id) ? this.selected.filter(x => x !== id) : [...this.selected, id];
                },
                availableLayanan() {
                    // Paket Wedding hanya relevan untuk acara Wedding.
                    return this.jenisAcara === 'lainnya'
                        ? this.layanan.filter(l => l.kategori !== 'paket_wedding')
                        : this.layanan;
                },
                filteredLayanan() {
                    const list = this.availableLayanan();
                    return this.filter === 'semua' ? list : list.filter(l => l.kategori === this.filter);
                },
                selectedItems() { return this.availableLayanan().filter(l => this.selected.includes(l.id)); },
                total() { return this.selectedItems().reduce((s, l) => s + Number(l.harga), 0); },
                fmt(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); },
                kategoriLabel(k) {
                    return { paket_wedding: 'Paket Wedding', makeup_only: 'Makeup Only', tambahan: 'Tambahan' }[k] || k;
                },
                openInfoModal(l) { this.activeModalData = l; this.isModalOpen = true; document.body.style.overflow = 'hidden'; },
                closeInfoModal() { this.isModalOpen = false; document.body.style.overflow = ''; setTimeout(() => { this.activeModalData = null; }, 300); },
                async cekTanggal() {
                    if (!this.tanggal) { this.tanggalTerisi = false; return; }
                    try {
                        const res = await fetch(this.cekUrl + '?tanggal=' + encodeURIComponent(this.tanggal));
                        const data = await res.json();
                        this.tanggalTerisi = !!data.terisi;
                    } catch (e) { this.tanggalTerisi = false; }
                },

                step: {{ old('jenis_acara') ? 2 : 1 }},
                mobileSummaryOpen: false,
                jenisAcara: '{{ old('jenis_acara') }}',
                namaAcara: '{{ old('nama_acara') }}',
                jumlah_tamu: '{{ old('jumlah_tamu') }}',
                lokasi: '{{ old('lokasi') }}',
                nama_pria: '{{ old('nama_pria') }}',
                nama_wanita: '{{ old('nama_wanita') }}',
                phone: '{{ old('phone', auth()->user()->phone) }}',
                email: '{{ old('email', auth()->user()->email) }}',

                stepValid(n) {
                    if (n === 1) return !!this.jenisAcara;
                    if (n === 2) {
                        const dasar = !!this.nama_pria && !!this.phone && !!this.tanggal && !!this.lokasi;
                        return this.jenisAcara === 'wedding'
                            ? (dasar && !!this.nama_wanita)
                            : (dasar && !!this.namaAcara);
                    }
                    if (n === 3) return this.selected.length > 0;
                    return true;
                },
                stepErrorMsg(n) {
                    if (n === 1) return 'Pilih jenis acara terlebih dahulu (Wedding / Non-Wedding).';
                    if (n === 2) return this.jenisAcara === 'wedding'
                        ? 'Lengkapi nama mempelai, WhatsApp, tanggal, dan lokasi terlebih dahulu.'
                        : 'Lengkapi nama pemesan, jenis acara, WhatsApp, tanggal, dan lokasi terlebih dahulu.';
                    if (n === 3) return 'Pilih minimal satu layanan terlebih dahulu.';
                    return '';
                },
                goStep(n) {
                    if (n > this.step && !this.stepValid(this.step)) {
                        window.tafToast('warn', this.stepErrorMsg(this.step));
                        return;
                    }
                    this.step = n;
                    this.mobileSummaryOpen = false;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                next() { this.goStep(Math.min(4, this.step + 1)); },
                prev() { this.goStep(Math.max(1, this.step - 1)); },

                syncBeforeSubmit(e) {
                    for (let i = 1; i <= 3; i++) {
                        if (!this.stepValid(i)) {
                            e.preventDefault();
                            this.step = i;
                            window.tafToast('warn', this.stepErrorMsg(i));
                            return;
                        }
                    }
                },
            };
        }
    </script>
@endpush