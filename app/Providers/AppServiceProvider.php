<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Reparto;
use App\Models\Pago;
use App\Observers\RepartoObserver;
use App\Observers\PagoObserver;

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
        // Forzar HTTPS en producción
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Registrar observers
        Reparto::observe(RepartoObserver::class);
        Pago::observe(PagoObserver::class);
    }
}
