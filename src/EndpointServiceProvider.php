<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints;

use Illuminate\Support\ServiceProvider;

class EndpointServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/endpoints.php', 'endpoints');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/endpoints.php' => config_path('endpoints.php'),
        ], 'endpoints-config');

        if (!$this->app->routesAreCached()) {
            $this->app->call(EndpointRegistrar::class);
        }
    }
}
