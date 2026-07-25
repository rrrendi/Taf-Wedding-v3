<x-guest-layout>
    <div class="login-box">
        <h2>Daftar <em>Akun</em></h2>
        <p class="login-sub">Buat akun untuk memesan layanan Taf Wedding</p>

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="field">
                <label for="name">Nama Lengkap</label>
                <input id="name" class="input" type="text" name="name" value="{{ old('name') }}"
                    placeholder="Nama lengkap Anda" required autofocus autocomplete="name">
            </div>
            <div class="field">
                <label for="phone">No. WhatsApp</label>
                <input id="phone" class="input" type="text" name="phone" value="{{ old('phone') }}"
                    placeholder="08xxxxxxxxxx" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" class="input" type="email" name="email" value="{{ old('email') }}"
                    placeholder="email@anda.com" required autocomplete="username">
            </div>
            
            <div class="field">
                <label for="password">Password</label>
                <div style="position: relative;">
                    <input id="password" class="input" type="password" name="password" placeholder="Minimal 8 karakter"
                        required autocomplete="new-password" style="padding-right: 40px;">
                    <button type="button" onclick="let input = this.parentElement.querySelector('input'); let iconShow = this.querySelector('.ico-show'); let iconHide = this.querySelector('.ico-hide'); if(input.type === 'password') { input.type = 'text'; iconShow.style.display = 'none'; iconHide.style.display = 'block'; } else { input.type = 'password'; iconShow.style.display = 'block'; iconHide.style.display = 'none'; }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 0; color: #888; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <svg class="ico-show" style="display: block;" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg class="ico-hide" style="display: none;" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                    </button>
                </div>
            </div>
            
            <div class="field">
                <label for="password_confirmation">Konfirmasi Password</label>
                <div style="position: relative;">
                    <input id="password_confirmation" class="input" type="password" name="password_confirmation"
                        placeholder="Ulangi password" required autocomplete="new-password" style="padding-right: 40px;">
                    <button type="button" onclick="let input = this.parentElement.querySelector('input'); let iconShow = this.querySelector('.ico-show'); let iconHide = this.querySelector('.ico-hide'); if(input.type === 'password') { input.type = 'text'; iconShow.style.display = 'none'; iconHide.style.display = 'block'; } else { input.type = 'password'; iconShow.style.display = 'block'; iconHide.style.display = 'none'; }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 0; color: #888; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        <svg class="ico-show" style="display: block;" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg class="ico-hide" style="display: none;" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-gold btn-full">Daftar</button>
        </form>
        <br>
        <div class="back-link">
            Sudah punya akun? <a href="{{ route('login') }}"><span>Masuk di sini</span></a>
        </div>
        <div class="back-link" style="margin-top:8px;">
            <a href="{{ route('landing') }}"><span>← Kembali ke Beranda</span></a>
        </div>
    </div>
</x-guest-layout>