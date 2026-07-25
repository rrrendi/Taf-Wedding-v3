@php $l = $layanan ?? null; @endphp
<div class="row">
    <div class="field">
        <label>Nama Layanan</label>
        <input class="input" name="nama" value="{{ old('nama', $l->nama ?? '') }}" placeholder="cth: Makeup Pengantin" required>
    </div>
    <div class="field">
        <label>Kategori</label>
        <select class="input" name="kategori" required>
            @foreach (['paket_wedding'=>'Paket Wedding','makeup_only'=>'Makeup Only','tambahan'=>'Tambahan'] as $val=>$lbl)
                <option value="{{ $val }}" @selected(old('kategori', $l->kategori ?? 'paket_wedding') === $val)>{{ $lbl }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="field">
    <label>Harga (Rp)</label>
    <input class="input" type="number" name="harga" min="0" value="{{ old('harga', $l ? (int) $l->harga : '') }}" placeholder="3500000" required>
</div>

{{-- BAGIAN BARU: GAMBAR & DESKRIPSI UNTUK POPUP KLIEN --}}
<div class="field" style="margin-bottom: 6px;">
    <label>Deskripsi Detail (Untuk Info Klien)</label>
    <textarea class="input" name="deskripsi" placeholder="Jelaskan detail yang didapat dari paket ini..." style="height: 20px; resize: vertical;">{{ old('deskripsi', $l->deskripsi ?? '') }}</textarea>
</div>

<div class="field" style="margin-bottom: 0;" x-data="{ 
        previewUrl: '{{ $l && $l->gambar ? $l->gambar_url : '' }}',
        fileChosen(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
            } else {
                this.previewUrl = '{{ $l && $l->gambar ? $l->gambar_url : '' }}';
            }
        }
    }">
        <label>Foto/Ilustrasi Paket (Opsional)</label>
        <label class="upload" style="display:block; text-align:center; padding:0; overflow:hidden; cursor:pointer;" @click.prevent="$refs.foto.click()">

            {{-- TAMPILAN AWAL (Belum ada foto) --}}
            <div x-show="!previewUrl" style="padding: 32px 16px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--gold2); width:36px; height:36px; margin-bottom:12px;">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                    <polyline points="21 15 16 10 5 21"></polyline>
                </svg>
                <div style="font-size:13px; font-weight:600; color:var(--ink3);">Ketuk untuk memilih foto...</div>
            </div>

            {{-- TAMPILAN PREVIEW (Sudah ada foto) --}}
            <div x-show="previewUrl" x-cloak style="position:relative; width:100%; aspect-ratio:4/3; background:var(--bg2);">
                <img :src="previewUrl" style="width:100%; height:100%; object-fit:cover; display:block;" alt="Preview">
                <div style="position:absolute; bottom:12px; right:12px;">
                    <span class="badge b-gold" style="box-shadow:0 4px 10px rgba(0,0,0,0.3);">Ketuk untuk Ganti</span>
                </div>
            </div>
        </label>
        
        <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" x-ref="foto" @change="fileChosen" style="display:none;">
        <span style="font-size: 11px; color: var(--muted); margin-top: 6px; display: block; line-height: 1.4;">*Format: JPG/PNG/WEBP. Maksimal 3MB. Gambar ini akan tampil saat klien menekan tombol <strong>[?]</strong> pada form pemesanan.</span>
    </div>

<div class="field" style="margin-top: 16px;">
    <label class="flex-gap" style="text-transform:none;letter-spacing:0;font-size:13px;color:var(--ink3);cursor:pointer;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $l->is_active ?? true))>
        Layanan aktif (tampil di landing &amp; form pemesanan klien)
    </label>
</div>