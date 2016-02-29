<?php namespace Jeylabs\SmartRoute;

use Illuminate\Support\ServiceProvider;

class SmartRouteServiceProvider extends ServiceProvider
{
    public function register()
    {
    }
    public function boot()
    {

        $this->publishes([
            __DIR__.'/config/smart-route.php' => config_path('smart-route.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/config/smart-route.php', 'smart-route'
        );

        if (! $this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }

    }
}