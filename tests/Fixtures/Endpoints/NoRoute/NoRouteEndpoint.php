<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\NoRoute;

use Jasonej\LaravelEndpoints\Endpoint;

/**
 * An endpoint with no route attribute. It is discovered but must not
 * register any routes.
 */
final class NoRouteEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
