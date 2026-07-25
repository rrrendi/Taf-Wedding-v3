@extends('layouts.admin')

@section('title', 'Tambah Layanan — Taf Wedding')

@section('content')
<div class="fade">
    <div class="pg-head">
        <div><h1>Tambah Layanan</h1><p>Tambahkan layanan baru ke dalam sistem</p></div>
        <a href="{{ route('admin.layanan.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>

    <div class="card" style="max-width:640px;">
        <div class="card-b">
            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('admin.layanan.store') }}" enctype="multipart/form-data">
                @csrf
                @include('admin.layanan._form')
                <button type="submit" class="btn btn-gold btn-full">Simpan Layanan</button>
            </form>
        </div>
    </div>
</div>
@endsection
