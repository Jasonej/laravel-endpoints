<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Simple;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Endpoint;

#[Get('/simple', name: 'endpoints.simple')]
final class SimpleGetEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
