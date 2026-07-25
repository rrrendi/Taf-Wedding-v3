<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tanggal berbahasa Indonesia (translatedFormat) di seluruh aplikasi.
        Carbon::setLocale('id');

        // Paginator memakai markup Bootstrap-5 yang sudah distyle di partials/styles.
        Paginator::useBootstrapFive();

        // if (app()->environment('local')) {
        //     URL::forceScheme('https');
        // } //cloudflared tunnel --url http://127.0.0.1:8000 --protocol http2
    }
}
