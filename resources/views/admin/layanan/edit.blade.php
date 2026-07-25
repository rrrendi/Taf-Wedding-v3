@extends('layouts.admin')

@section('title', 'Edit Layanan — Taf Wedding')

@section('content')
<div class="fade">
    <div class="pg-head">
        <div><h1>Edit Layanan</h1><p>Perbarui data layanan: {{ $layanan->nama }}</p></div>
        <a href="{{ route('admin.layanan.index') }}" class="btn btn-outline btn-sm">← Kembali</a>
    </div>

    <div class="card" style="max-width:640px;">
        <div class="card-b">
            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('admin.layanan.update', $layanan) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('admin.layanan._form', ['layanan' => $layanan])
                <button type="submit" class="btn btn-gold btn-full">Perbarui Layanan</button>
            </form>
        </div>
    </div>
</div>
@endsection
