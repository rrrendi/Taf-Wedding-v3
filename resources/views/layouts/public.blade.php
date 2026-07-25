<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Taf Wedding — Sistem Manajemen Pemesanan')</title>
    <link rel="icon" href="{{ asset('images/taf-logo-dark.png') }}">
    @include('partials.styles')
    @stack('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    @yield('content')
    @stack('scripts')

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

    <script>
        // Notif mandiri (tidak bergantung tafToast) untuk pesan yang dititipkan
        // lewat sessionStorage sebelum berpindah halaman (mis. dari tombol
        // "+ Buat Pesanan Baru" saat masih ada pesanan aktif).
        function tafTampilkanNotif(pesan, durasiMs) {
            durasiMs = durasiMs || 5000;
            var el = document.createElement('div');
            el.textContent = pesan;
            el.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);'
                + 'background:#1C1710;color:#F4E8CC;border:1.5px solid rgba(231,200,121,.4);'
                + 'padding:12px 20px;border-radius:10px;font-size:13px;font-weight:600;'
                + 'max-width:90%;text-align:center;z-index:99999;box-shadow:0 10px 30px rgba(0,0,0,.35);'
                + 'opacity:0;transition:opacity .25s ease;';
            document.body.appendChild(el);
            requestAnimationFrame(function () { el.style.opacity = '1'; });
            setTimeout(function () {
                el.style.opacity = '0';
                setTimeout(function () { el.remove(); }, 250);
            }, durasiMs);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var pesanTitipan = sessionStorage.getItem('tafNoticeAktif');
            if (pesanTitipan) {
                sessionStorage.removeItem('tafNoticeAktif');
                tafTampilkanNotif(pesanTitipan);
            }
        });
    </script>

</body>

</html>