<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\ScopeBindings;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Endpoint;

#[Get('/scope-default', name: 'endpoints.scope.default')]
final class ScopeDefaultEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
