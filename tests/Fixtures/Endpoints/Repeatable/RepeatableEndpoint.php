<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Repeatable;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Attributes\Post;
use Jasonej\LaravelEndpoints\Endpoint;

#[Get('/repeatable', name: 'endpoints.repeatable.get')]
#[Post('/repeatable', name: 'endpoints.repeatable.post')]
final class RepeatableEndpoint extends Endpoint
{
    public function __invoke(): string
    {
        return 'ok';
    }
}
