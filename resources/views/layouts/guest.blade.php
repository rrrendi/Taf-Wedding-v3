<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk — Taf Wedding')</title>
    @include('partials.styles')
</head>
<body>
    <div class="login-page">
        {{ $slot }}
    </div>
</body>
</html>
