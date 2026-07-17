<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Modifiers;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Endpoint;
use Jasonej\LaravelEndpoints\Tests\Fixtures\RouteName;

#[Get('/enum-name', name: RouteName::Enum)]
final class EnumNameEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
