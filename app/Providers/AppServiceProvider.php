<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Belt-and-braces behind TLS-terminating proxies (Vercel): any URL the
        // app generates in production must use https to avoid mixed content.
        // Request scheme detection is fixed via trustProxies in bootstrap/app.php.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
