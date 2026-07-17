<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Modifiers;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Endpoint;

#[Get('/domain', name: 'endpoints.domain', domain: 'api.example.com')]
final class DomainEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
