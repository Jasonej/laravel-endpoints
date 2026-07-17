<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Route;
use Jasonej\LaravelEndpoints\EndpointRegistrar;
use Jasonej\LaravelEndpoints\EndpointServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [EndpointServiceProvider::class];
    }

    /**
     * Discover and register the endpoints found in one or more fixture
     * directories (relative to tests/Fixtures/Endpoints).
     */
    protected function registerEndpointsIn(string ...$directories): void
    {
        $paths = array_map(
            fn(string $directory): string => __DIR__.'/Fixtures/Endpoints/'.$directory,
            $directories,
        );

        $this->registerEndpointsFromConfig($paths);
    }

    /**
     * Run the registrar against an arbitrary set of configured paths.
     *
     * @param  array<array-key, mixed>  $paths
     */
    protected function registerEndpointsFromConfig(array $paths): void
    {
        config()->set('endpoints.paths', $paths);

        $this->app->call(EndpointRegistrar::class);
    }

    protected function route(string $name): ?Route
    {
        $routes = $this->app->make('router')->getRoutes();

        // Names are applied after the route is added to the collection, so the
        // name lookup must be refreshed before it can be queried.
        $routes->refreshNameLookups();

        return $routes->getByName($name);
    }
}
