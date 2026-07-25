@extends('layouts.admin')

@section('title', 'Kelola Layanan — Taf Wedding')

@section('content')
<div class="fade">
    <div class="pg-head">
        <div><h1>Kelola Layanan</h1><p>Tambah, ubah, atau hapus layanan</p></div>
        <a href="{{ route('admin.layanan.create') }}" class="btn btn-gold btn-sm">+ Tambah</a>
    </div>

    <div class="card">
        <div class="tbl-wrap">
            <table class="rtable">
                <thead><tr><th>Nama Layanan</th><th>Kategori</th><th>Harga</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse ($layanans as $l)
                        <tr>
                            <td data-label="Nama"><strong>{{ $l->nama }}</strong><div style="font-size:11.5px;color:var(--muted);margin-top:2px;">{{ Str::limit($l->deskripsi, 50) }}</div></td>
                            <td data-label="Kategori"><span class="badge b-gold">{{ $l->kategori_label }}</span></td>
                            <td data-label="Harga"><strong>{{ $l->harga_format }}</strong></td>
                            <td data-label="Status"><span class="badge {{ $l->is_active ? 'b-green' : 'b-red' }}">{{ $l->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td data-label="" class="cell-actions">
                                <div class="flex-gap">
                                    <a href="{{ route('admin.layanan.edit', $l) }}" class="btn btn-outline btn-sm">Edit</a>
                                    <form method="POST" action="{{ route('admin.layanan.destroy', $l) }}" onsubmit="return confirm('Hapus layanan {{ $l->nama }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline btn-sm" style="color:var(--red);border-color:var(--red);">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted" style="text-align:center;padding:28px;">Belum ada layanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
