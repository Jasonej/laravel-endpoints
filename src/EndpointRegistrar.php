<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use InvalidArgumentException;
use Jasonej\LaravelEndpoints\Attributes\Route as RouteAttribute;
use RuntimeException;
use Spatie\Attributes\Attributes;
use Spatie\StructureDiscoverer\Discover;
use Throwable;

readonly class EndpointRegistrar
{
    public function __construct(
        private Application $app,
        private ExceptionHandler $exceptions,
        private Repository $config,
        private Router $router,
    ) {
        //
    }

    public function __invoke(): void
    {
        $paths = $this->discoverablePaths();

        if (empty($paths)) {
            return;
        }

        $discovered = Discover::in(...$paths)
            ->classes()
            ->extending(Endpoint::class)
            ->get();

        foreach ($discovered as $discoveredClass) {
            if (!is_string($discoveredClass) || !is_subclass_of($discoveredClass, Endpoint::class)) {
                continue;
            }

            $this->processEndpoint($discoveredClass);
        }
    }

    /**
     * @phpstan-assert-if-true string $path
     */
    private function checkPath(mixed $path): bool
    {
        if (!is_string($path)) {
            $this->throwOrReport(new InvalidArgumentException(
                'The "endpoints.paths" configuration value must be an array of strings.',
            ));

            return false;
        }

        if (!is_dir($path)) {
            $this->throwOrReport(new RuntimeException(sprintf(
                'Configured endpoint path "%s" does not exist.',
                $path,
            )));

            return false;
        }

        return true;
    }

    /**
     * @return list<string>
     */
    private function discoverablePaths(): array
    {
        return array_values(array_filter($this->config->array('endpoints.paths'), $this->checkPath(...)));
    }

    /**
     * @param class-string<Endpoint> $discoveredClass
     */
    private function processEndpoint(string $discoveredClass): void
    {
        $routeAttributes = Attributes::getAll($discoveredClass, RouteAttribute::class);

        foreach ($routeAttributes as $routeAttribute) {
            $route = $this->router->match(
                methods: $routeAttribute->methods,
                uri: $routeAttribute->path,
                action: $discoveredClass,
            );

            if (!empty($routeAttribute->domain)) {
                $route->domain($routeAttribute->domain);
            }

            if (!empty($routeAttribute->middleware)) {
                $route->middleware($routeAttribute->middleware);
            }

            if (!empty($routeAttribute->name)) {
                $route->name($routeAttribute->name);
            }

            if ($routeAttribute->scopeBindings === false) {
                $route->withoutScopedBindings();
            } elseif ($routeAttribute->scopeBindings === true) {
                $route->scopeBindings();
            }

            if (!empty($routeAttribute->wheres)) {
                $route->where($routeAttribute->wheres);
            }

            if (!empty($routeAttribute->withoutMiddleware)) {
                $route->withoutMiddleware($routeAttribute->withoutMiddleware);
            }
        }
    }

    private function throwOrReport(Throwable $throwable): void
    {
        if ($this->app->isLocal()) {
            throw $throwable;
        }

        $this->exceptions->report($throwable);
    }
}
