<x-app-layout>
    <x-slot name="header">
        <div class="pg-head"><div><h1 style="font-family:var(--serif);font-size:24px;">Profil Saya</h1><p style="color:var(--muted);font-size:13px;">Perbarui informasi akun Anda</p></div></div>
    </x-slot>

    @if ($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    {{-- Update profil --}}
    <div class="card">
        <div class="card-h"><span class="card-t">Informasi Akun</span></div>
        <div class="card-b">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')
                <div class="row">
                    <div class="field">
                        <label>Nama</label>
                        <input class="input" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                    </div>
                    <div class="field">
                        <label>No. WhatsApp</label>
                        <input class="input" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                    </div>
                </div>
                <div class="field">
                    <label>Email</label>
                    <input class="input" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                </div>
                <button type="submit" class="btn btn-gold">Simpan Perubahan</button>
                @if (session('status') === 'profile-updated')
                    <span class="gold" style="font-size:12px;margin-left:10px;">✓ Tersimpan.</span>
                @endif
            </form>
        </div>
    </div>

    {{-- Hapus akun --}}
    <div class="card">
        <div class="card-h"><span class="card-t" style="color:var(--red);">Hapus Akun</span></div>
        <div class="card-b">
            <p class="muted" style="font-size:12.5px;margin-bottom:14px;">Setelah akun dihapus, seluruh data terkait akan dihapus permanen.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Yakin ingin menghapus akun ini secara permanen?')">
                @csrf @method('DELETE')
                <div class="field" style="max-width:320px;">
                    <label>Konfirmasi Password</label>
                    <input class="input" type="password" name="password" placeholder="Masukkan password Anda" required>
                </div>
                <button type="submit" class="btn btn-outline" style="color:var(--red);border-color:var(--red);">Hapus Akun Saya</button>
            </form>
        </div>
    </div>
</x-app-layout>
