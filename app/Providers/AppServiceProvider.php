<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force le schéma https quand APP_URL est en https (déploiement Railway),
        // pour que @vite() / asset() / route() génèrent des URLs https://.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        \App\Models\Besoin::observe(\App\Observers\BesoinObserver::class);
    }

}
