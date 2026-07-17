<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Feature;

use Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Modifiers\DomainEndpoint;
use Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Simple\SimpleGetEndpoint;
use Jasonej\LaravelEndpoints\Tests\Fixtures\RouteName;
use Jasonej\LaravelEndpoints\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class EndpointRegistrarTest extends TestCase
{
    #[Test]
    public function itRegistersARoutePointingAtTheEndpointAsAnInvokableAction(): void
    {
        $this->registerEndpointsIn('Simple');

        $route = $this->route('endpoints.simple');

        $this->assertNotNull($route);
        $this->assertSame('simple', $route->uri());
        $this->assertContains('GET', $route->methods());
        $this->assertContains('HEAD', $route->methods());
        $this->assertSame(SimpleGetEndpoint::class, $route->getActionName());
    }

    #[Test]
    public function itAppliesTheDomainWhenConfigured(): void
    {
        $this->registerEndpointsIn('Modifiers');

        $route = $this->route('endpoints.domain');

        $this->assertNotNull($route);
        $this->assertSame('api.example.com', $route->getDomain());
        $this->assertSame(DomainEndpoint::class, $route->getActionName());
    }

    #[Test]
    public function itAppliesMiddlewareAndExcludedMiddleware(): void
    {
        $this->registerEndpointsIn('Modifiers');

        $route = $this->route('endpoints.middleware');

        $this->assertNotNull($route);
        $this->assertContains('first', $route->middleware());
        $this->assertContains('second', $route->middleware());
        $this->assertContains('second', $route->excludedMiddleware());
    }

    #[Test]
    public function itAppliesWhereConstraints(): void
    {
        $this->registerEndpointsIn('Modifiers');

        $route = $this->route('endpoints.wheres');

        $this->assertNotNull($route);
        $this->assertSame(['id' => '[0-9]+'], $route->wheres);
    }

    #[Test]
    public function itAcceptsABackedEnumAsTheRouteName(): void
    {
        $this->registerEndpointsIn('Modifiers');

        $route = $this->route(RouteName::Enum->value);

        $this->assertNotNull($route);
        $this->assertSame(RouteName::Enum->value, $route->getName());
    }

    #[Test]
    public function itRegistersOneRoutePerRepeatedAttribute(): void
    {
        $this->registerEndpointsIn('Repeatable');

        $get = $this->route('endpoints.repeatable.get');
        $post = $this->route('endpoints.repeatable.post');

        $this->assertNotNull($get);
        $this->assertNotNull($post);
        $this->assertContains('GET', $get->methods());
        $this->assertContains('POST', $post->methods());
    }

    #[Test]
    public function itDoesNotRegisterRoutesForEndpointsWithoutARouteAttribute(): void
    {
        $before = $this->app->make('router')->getRoutes()->count();

        $this->registerEndpointsIn('NoRoute');

        $after = $this->app->make('router')->getRoutes()->count();

        $this->assertSame($before, $after);
    }
}
