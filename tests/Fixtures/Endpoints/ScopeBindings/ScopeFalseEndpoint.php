<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\ScopeBindings;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Endpoint;

#[Get('/scope-false', name: 'endpoints.scope.false', scopeBindings: false)]
final class ScopeFalseEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
