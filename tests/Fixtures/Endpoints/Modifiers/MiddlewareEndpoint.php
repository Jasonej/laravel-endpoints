<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Modifiers;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Endpoint;

#[Get(
    '/middleware',
    name: 'endpoints.middleware',
    middleware: ['first', 'second'],
    withoutMiddleware: ['second'],
)]
final class MiddlewareEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
