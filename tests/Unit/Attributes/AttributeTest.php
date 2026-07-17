<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Unit\Attributes;

use Jasonej\LaravelEndpoints\Attributes\Delete;
use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Attributes\Options;
use Jasonej\LaravelEndpoints\Attributes\Patch;
use Jasonej\LaravelEndpoints\Attributes\Post;
use Jasonej\LaravelEndpoints\Attributes\Put;
use Jasonej\LaravelEndpoints\Attributes\Route;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AttributeTest extends TestCase
{
    /**
     * @param  class-string<Route>  $attribute
     * @param  list<string>  $expectedMethods
     */
    #[Test]
    #[DataProvider('verbProvider')]
    public function verbAttributesMapToTheCorrectHttpMethods(string $attribute, array $expectedMethods): void
    {
        $route = new $attribute(path: '/example');

        $this->assertSame($expectedMethods, $route->methods);
        $this->assertSame('/example', $route->path);
    }

    /**
     * @return iterable<string, array{class-string<Route>, list<string>}>
     */
    public static function verbProvider(): iterable
    {
        yield 'get' => [Get::class, ['GET', 'HEAD']];
        yield 'post' => [Post::class, ['POST']];
        yield 'put' => [Put::class, ['PUT']];
        yield 'patch' => [Patch::class, ['PATCH']];
        yield 'delete' => [Delete::class, ['DELETE']];
        yield 'options' => [Options::class, ['OPTIONS']];
    }

    #[Test]
    public function theBaseRouteAttributeAcceptsArbitraryMethods(): void
    {
        $route = new Route(methods: ['GET', 'POST'], path: '/example');

        $this->assertSame(['GET', 'POST'], $route->methods);
    }

    #[Test]
    public function verbAttributesForwardEveryOptionToTheBaseRoute(): void
    {
        $route = new Get(
            path: '/example',
            name: 'example.name',
            middleware: ['auth'],
            withoutMiddleware: ['throttle'],
            domain: 'api.example.com',
            wheres: ['id' => '[0-9]+'],
            scopeBindings: true,
        );

        $this->assertSame('example.name', $route->name);
        $this->assertSame(['auth'], $route->middleware);
        $this->assertSame(['throttle'], $route->withoutMiddleware);
        $this->assertSame('api.example.com', $route->domain);
        $this->assertSame(['id' => '[0-9]+'], $route->wheres);
        $this->assertTrue($route->scopeBindings);
    }

    #[Test]
    public function optionsDefaultToSensibleEmptyValues(): void
    {
        $route = new Get(path: '/example');

        $this->assertNull($route->name);
        $this->assertSame([], $route->middleware);
        $this->assertSame([], $route->withoutMiddleware);
        $this->assertNull($route->domain);
        $this->assertSame([], $route->wheres);
        $this->assertNull($route->scopeBindings);
    }
}
