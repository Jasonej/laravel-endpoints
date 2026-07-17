<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Feature;

use Illuminate\Contracts\Debug\ExceptionHandler;
use InvalidArgumentException;
use Jasonej\LaravelEndpoints\Tests\TestCase;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class EndpointRegistrarPathsTest extends TestCase
{
    #[Test]
    public function emptyPathsRegisterNothing(): void
    {
        $before = $this->app->make('router')->getRoutes()->count();

        $this->registerEndpointsFromConfig([]);

        $this->assertSame($before, $this->app->make('router')->getRoutes()->count());
    }

    #[Test]
    public function aMissingDirectoryThrowsInTheLocalEnvironment(): void
    {
        $this->app['env'] = 'local';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('does not exist');

        $this->registerEndpointsFromConfig(['/this/path/does/not/exist']);
    }

    #[Test]
    public function aNonStringPathThrowsInTheLocalEnvironment(): void
    {
        $this->app['env'] = 'local';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must be an array of strings');

        $this->registerEndpointsFromConfig([123]);
    }

    #[Test]
    public function aMissingDirectoryIsReportedOutsideTheLocalEnvironment(): void
    {
        $this->assertFalse($this->app->isLocal());

        $this->mock(ExceptionHandler::class, function (MockInterface $mock): void {
            $mock->shouldReceive('report')
                ->once()
                ->with(Mockery::type(RuntimeException::class));
        });

        $before = $this->app->make('router')->getRoutes()->count();

        $this->registerEndpointsFromConfig(['/this/path/does/not/exist']);

        $this->assertSame($before, $this->app->make('router')->getRoutes()->count());
    }

    #[Test]
    public function validDirectoriesAreStillDiscoveredWhenAnotherPathIsInvalid(): void
    {
        $this->mock(ExceptionHandler::class, function (MockInterface $mock): void {
            $mock->shouldReceive('report')->once();
        });

        $this->registerEndpointsFromConfig([
            '/this/path/does/not/exist',
            __DIR__.'/../Fixtures/Endpoints/Simple',
        ]);

        $this->assertNotNull($this->route('endpoints.simple'));
    }
}
