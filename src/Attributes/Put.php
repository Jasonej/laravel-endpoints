<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Attributes;

use Attribute;
use BackedEnum;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
readonly class Put extends Route
{
    /**
     * @param array<array-key, string|class-string> $middleware
     * @param array<array-key, string|class-string> $withoutMiddleware
     * @param array<string, string> $wheres
     */
    public function __construct(
        string $path,
        BackedEnum|string|null $name = null,
        array $middleware = [],
        array $withoutMiddleware = [],
        ?string $domain = null,
        array $wheres = [],
        ?bool $scopeBindings = null,
    ) {
        parent::__construct(
            methods: ['PUT'],
            path: $path,
            name: $name,
            middleware: $middleware,
            withoutMiddleware: $withoutMiddleware,
            domain: $domain,
            wheres: $wheres,
            scopeBindings: $scopeBindings,
        );
    }
}
