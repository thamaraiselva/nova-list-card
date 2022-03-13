<?php

namespace NovaListCard;

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('list-card', __DIR__ . '/../dist/js/card.js');
        });
    }

    /**
     * Register the card's routes.
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware([ 'nova' ])
             ->prefix('nova-vendor/nova-list-card')
             ->group(function () {
                 Route::get(
                     '/{key}/{aggregate?}/{relationship?}/{column?}',
                     \NovaListCard\Http\Controllers\ResourceController::class
                 );
             });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }
}
