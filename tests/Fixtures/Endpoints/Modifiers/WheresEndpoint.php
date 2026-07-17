<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Modifiers;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Endpoint;

#[Get('/wheres/{id}', name: 'endpoints.wheres', wheres: ['id' => '[0-9]+'])]
final class WheresEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
