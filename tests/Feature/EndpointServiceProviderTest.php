<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Feature;

use Illuminate\Support\ServiceProvider;
use Jasonej\LaravelEndpoints\EndpointServiceProvider;
use Jasonej\LaravelEndpoints\Tests\TestCase;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

final class EndpointServiceProviderTest extends TestCase
{
    #[Test]
    public function itMergesTheDefaultConfig(): void
    {
        $this->assertSame([app_path()], config('endpoints.paths'));
    }

    #[Test]
    public function itPublishesTheConfigUnderTheEndpointsConfigTag(): void
    {
        $paths = ServiceProvider::pathsToPublish(EndpointServiceProvider::class, 'endpoints-config');

        $this->assertContains(config_path('endpoints.php'), $paths);

        $source = array_key_first($paths);
        $this->assertIsString($source);
        $this->assertStringEndsWith('config/endpoints.php', $source);
    }

    #[Test]
    public function itRegistersEndpointsOnBootWhenRoutesAreNotCached(): void
    {
        config()->set('endpoints.paths', [__DIR__.'/../Fixtures/Endpoints/Simple']);

        (new EndpointServiceProvider($this->app))->boot();

        $this->assertNotNull($this->route('endpoints.simple'));
    }

    #[Test]
    public function itSkipsRegistrationWhenRoutesAreCached(): void
    {
        config()->set('endpoints.paths', [__DIR__.'/../Fixtures/Endpoints/Simple']);

        $app = Mockery::mock($this->app)->makePartial();
        $app->shouldReceive('routesAreCached')->andReturnTrue();

        (new EndpointServiceProvider($app))->boot();

        $this->assertNull($this->route('endpoints.simple'));
    }
}
