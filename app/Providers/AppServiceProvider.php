<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
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

        View::composer('layouts.app', function ($view) {
            $notifications = [];
            $unreadNotificationsCount = 0;
            $user = Auth::user();

            if ($user && $user->role === 'repartidor') {
                $pendientesHoy = Reparto::with('cliente')
                    ->where('repartidor_id', $user->id)
                    ->where('estado', 'pendiente')
                    ->whereDate('fecha_programada', today())
                    ->orderBy('fecha_programada')
                    ->take(5)
                    ->get();

                foreach ($pendientesHoy as $reparto) {
                    $notifications[] = [
                        'title' => 'Entrega pendiente',
                        'message' => $reparto->cliente->nombre . ' - ' . $reparto->cantidad . ' bidones',
                        'time' => optional($reparto->fecha_programada)->format('d/m/Y'),
                        'url' => route('repartos.index'),
                        'priority' => 'normal',
                    ];
                }

                $atrasados = Reparto::where('repartidor_id', $user->id)
                    ->where('estado', 'pendiente')
                    ->whereDate('fecha_programada', '<', today())
                    ->count();

                if ($atrasados > 0) {
                    array_unshift($notifications, [
                        'title' => 'Atencion',
                        'message' => 'Tenes ' . $atrasados . ' entregas atrasadas',
                        'time' => 'Revisar hoy',
                        'url' => route('repartos.index'),
                        'priority' => 'high',
                    ]);
                }

                $unreadNotificationsCount = count($notifications);
            }

            $view->with('headerNotifications', $notifications)
                ->with('unreadNotificationsCount', $unreadNotificationsCount);
        });
    }
}
