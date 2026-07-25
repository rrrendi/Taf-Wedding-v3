@extends('layouts.admin')

@section('title', 'Edit Foto Galeri — Taf Wedding')

@section('content')
<div class="fade">
    <div class="pg-head">
        <div><h1>Edit Foto Galeri</h1><p>Perbarui detail atau ganti foto</p></div>
        <a href="{{ route('admin.galeri.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>

    <div class="card" style="max-width:560px;" x-data="{ 
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
        <div class="card-b">
            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.galeri.update', $galeri) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                
                {{-- Area Upload yang Menampilkan Foto Lama & Preview Foto Baru --}}
                <div class="field">
                    <label>File Foto</label>
                    <label class="upload" style="display:block; text-align:center; padding:0; overflow:hidden; cursor:pointer;" @click.prevent="$refs.foto.click()">
                        
                        {{-- TAMPILAN FOTO LAMA (Jika belum pilih foto baru) --}}
                        <div x-show="!previewUrl" style="position:relative; width:100%; aspect-ratio:16/9; background:{{ $galeri->gambar ? 'var(--bg2)' : ($galeri->warna ?? 'var(--bg3)') }};">
                            @if ($galeri->gambar)
                                <img src="{{ Storage::url($galeri->gambar) }}" alt="{{ $galeri->judul }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                            @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:var(--muted); font-size:13px;">Tidak ada foto.</div>
                            @endif
                            <div style="position:absolute; bottom:12px; right:12px;">
                                <span class="badge" style="background:rgba(0,0,0,0.6); color:#fff; border:1px solid rgba(255,255,255,0.2); backdrop-filter:blur(4px);">Ketuk untuk Ganti Foto</span>
                            </div>
                        </div>

                        {{-- TAMPILAN PREVIEW BARU (Jika sudah pilih foto) --}}
                        <div x-show="previewUrl" x-cloak style="position:relative; width:100%; aspect-ratio:16/9; background:var(--bg2);">
                            <img :src="previewUrl" style="width:100%; height:100%; object-fit:cover; display:block;" alt="Preview Baru">
                            <div style="position:absolute; bottom:12px; right:12px;">
                                <span class="badge b-gold" style="box-shadow:0 4px 10px rgba(0,0,0,0.3);">Ketuk untuk Ganti</span>
                            </div>
                        </div>
                        
                    </label>
                    <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" x-ref="foto" @change="fileChosen" style="display:none;">
                </div>

                <div class="field">
                    <label>Judul / Label Foto</label>
                    <input class="input" name="judul" value="{{ old('judul', $galeri->judul) }}" required>
                </div>

                <div class="row">
                    <div class="field">
                        <label>Urutan Tampil</label>
                        <input class="input" type="number" name="urutan" min="0" value="{{ old('urutan', $galeri->urutan) }}">
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <select class="input" name="is_active">
                            <option value="1" @selected($galeri->is_active)>Tampilkan</option>
                            <option value="0" @selected(! $galeri->is_active)>Sembunyikan</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-gold btn-full mt-2">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection