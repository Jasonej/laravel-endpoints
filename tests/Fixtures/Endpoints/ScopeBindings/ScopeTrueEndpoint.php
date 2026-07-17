<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\ScopeBindings;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Endpoint;

#[Get('/scope-true', name: 'endpoints.scope.true', scopeBindings: true)]
final class ScopeTrueEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
