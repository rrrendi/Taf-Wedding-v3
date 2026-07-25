<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Taf Wedding')</title>
    <link rel="icon" href="{{ asset('images/taf-emblem.png') }}">
    @include('partials.styles')
    @stack('head')
</head>

<body>
    <div style="min-height:100vh;background:var(--bg);">
        {{-- Top bar --}}
        <div
            style="background:var(--hero);border-bottom:1px solid rgba(201,162,75,.14);position:sticky;top:0;z-index:50;">
            <div
                style="max-width:880px;margin:0 auto;padding:13px 20px;display:flex;justify-content:space-between;align-items:center;gap:12px;">
                <a href="{{ route('dashboard') }}" class="logo" style="color:#F4E9CF;">
                    <img src="{{ asset('images/taf-emblem.png') }}" alt="" class="logo-emblem" style="filter:none;">
                    Taf <em style="color:var(--gold3);">Wedding</em>
                </a>
                <div class="topnav-r">
                    <a href="{{ route('client.pemesanan.index') }}" class="btn btn-outline btn-sm"
                        style="background:rgba(201,162,75,.1);border-color:rgba(201,162,75,.3);color:#F4E9CF;">Portal
                        Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-gold btn-sm">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        @isset($header)
            <div style="max-width:880px;margin:28px auto 0;padding:0 20px;">
                {{ $header }}
            </div>
        @endisset

        <main style="max-width:880px;margin:0 auto;padding:24px 20px 60px;">
            {{ $slot }}
        </main>
    </div>

    @if (session('success') || session('error') || session('info') || session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if (session('success')) window.tafToast('success', @json(session('success'))); @endif
                @if (session('error'))   window.tafToast('error', @json(session('error'))); @endif
                @if (session('info'))    window.tafToast('info', @json(session('info'))); @endif
                @if (session('warning')) window.tafToast('warn', @json(session('warning'))); @endif
            });
        </script>
    @endif

</body>

</html>