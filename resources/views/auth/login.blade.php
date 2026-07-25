<x-guest-layout>
    <div class="login-box">
        <h2>Taf <em>Wedding</em></h2>
        <p class="login-sub">Masuk ke akun Anda</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" placeholder="email@anda.com" required autofocus autocomplete="username">
            </div>
            
            <div class="field">
                <label for="password">Password</label>
                <div style="position: relative;">
                    {{-- DIKEMBALIKAN: type="password" agar titik-titik aman secara default --}}
                    <input id="password" class="input" type="password" name="password" placeholder="••••••••" required autocomplete="current-password" style="padding-right: 40px;">
                    
                    {{-- Vanilla JS untuk Show/Hide Password dengan aman --}}
                    <button type="button" onclick="let input = this.parentElement.querySelector('input'); let iconShow = this.querySelector('.ico-show'); let iconHide = this.querySelector('.ico-hide'); if(input.type === 'password') { input.type = 'text'; iconShow.style.display = 'none'; iconHide.style.display = 'block'; } else { input.type = 'password'; iconShow.style.display = 'block'; iconHide.style.display = 'none'; }" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 0; color: #888; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                        
                        {{-- Ikon Mata Terbuka (Muncul saat password disembunyikan) --}}
                        <svg class="ico-show" style="display: block;" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        
                        {{-- Ikon Mata Tercoret (Muncul saat password ditampilkan) --}}
                        <svg class="ico-hide" style="display: none;" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                    </button>
                </div>
            </div>
            
            <div class="flex-between" style="margin-bottom:20px;">
                <label class="flex-gap" style="font-size:12px;color:var(--ink4);cursor:pointer;">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a class="pill-link" href="{{ route('password.request') }}">Lupa password?</a>
                @endif
            </div>
            <button type="submit" class="btn btn-gold btn-full">Masuk</button>
        </form>
        <br>
        <div class="back-link">
            Belum punya akun? <a href="{{ route('register') }}"><span>Daftar di sini</span></a>
        </div>
        <div class="back-link" style="margin-top:8px;">
            <a href="{{ route('landing') }}"><span>← Kembali ke Beranda</span></a>
        </div>
    </div>
</x-guest-layout>