@extends('layouts.admin')

@section('title', 'Galeri — Taf Wedding')

@section('content')
<div class="fade">
    <div class="pg-head">
        <div><h1>Galeri Landing Page</h1><p>Kelola foto yang tampil di halaman depan website</p></div>
        <a href="{{ route('landing') }}" target="_blank" class="btn btn-outline btn-sm">Lihat Landing</a>
    </div>

    <div class="grid2" style="align-items:start;">
        {{-- Form tambah dengan Preview yang Menyatu --}}
        <div class="card" x-data="{ 
            fotoName: '', 
            previewUrl: null,
            fileChosen(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fotoName = file.name;
                    this.previewUrl = URL.createObjectURL(file);
                } else {
                    this.fotoName = '';
                    this.previewUrl = null;
                }
            }
        }">
            <div class="card-h"><span class="card-t">Tambah Foto</span></div>
            <div class="card-b">
                <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="field">
                        <label>Judul / Label Foto</label>
                        <input class="input" name="judul" value="{{ old('judul') }}" placeholder="cth: Dekorasi Pelaminan" required>
                    </div>

                    {{-- Area Upload yang Berubah Menjadi Preview --}}
                    <div class="field">
                        <label>File Foto (JPG, PNG, WEBP — maks 5MB)</label>
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
                        <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" x-ref="foto" @change="fileChosen" style="display:none;" required>
                    </div>

                    <div class="row">
                        <div class="field">
                            <label>Urutan Tampil</label>
                            <input class="input" type="number" name="urutan" min="0" value="{{ old('urutan', 0) }}">
                        </div>
                        <div class="field">
                            <label>Status</label>
                            <select class="input" name="is_active">
                                <option value="1">Tampilkan</option>
                                <option value="0">Sembunyikan</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-gold btn-full">Unggah Foto</button>
                </form>
                <p class="muted" style="font-size:11.5px;margin-top:12px;">Foto akan tampil di bagian &ldquo;Galeri&rdquo; pada landing page, diurutkan dari nomor terkecil.</p>
            </div>
        </div>

        {{-- Daftar foto --}}
        <div class="card">
            <div class="card-h"><span class="card-t">Foto Saat Ini</span><span class="muted" style="font-size:12px;">{{ $galeris->count() }} foto</span></div>
            <div class="card-b">
                @if ($galeris->isEmpty())
                    <div class="empty">
                        <div class="empty-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2.5"/><circle cx="8.5" cy="9.5" r="1.6"/><path d="m4 17 4.5-4.5a2 2 0 0 1 2.8 0L20 21"/></svg></div>
                        <p>Belum ada foto galeri.</p>
                    </div>
                @else
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px;">
                        @foreach ($galeris as $g)
                            <div style="border:1px solid var(--border);border-radius:var(--r2);overflow:hidden;">
                                <div style="aspect-ratio:4/3;background:{{ $g->gambar ? 'var(--bg2)' : ($g->warna ?? 'var(--bg3)') }};position:relative;">
                                    @if ($g->gambar)
                                        <img src="{{ Storage::url($g->gambar) }}" alt="{{ $g->judul }}" style="width:100%;height:100%;object-fit:cover;">
                                    @endif
                                    @unless ($g->is_active)
                                        <span class="badge b-red" style="position:absolute;top:8px;left:8px;">Disembunyikan</span>
                                    @endunless
                                    <span class="badge b-gold" style="position:absolute;top:8px;right:8px;">#{{ $g->urutan }}</span>
                                </div>
                                <div style="padding:10px 12px;">
                                    <div style="font-weight:600;color:var(--ink);font-size:13px;">{{ $g->judul }}</div>
                                    <div class="flex-gap flex-wrap" style="margin-top:8px;">
                                        <a href="{{ route('admin.galeri.edit', $g) }}" class="btn btn-outline btn-sm" style="padding:5px 14px;font-size:11px;">Edit</a>
                                        <form method="POST" action="{{ route('admin.galeri.destroy', $g) }}" onsubmit="return confirm('Hapus foto ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-outline btn-sm" style="padding:5px 14px;font-size:11px;color:var(--red);border-color:var(--red);">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection