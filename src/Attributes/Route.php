<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Attributes;

use Attribute;
use BackedEnum;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
readonly class Route
{
    /**
     * @param list<string> $methods
     * @param array<array-key, string|class-string> $middleware
     * @param array<array-key, string|class-string> $withoutMiddleware
     * @param array<string, string> $wheres
     */
    public function __construct(
        public array $methods,
        public string $path,
        public BackedEnum|string|null $name = null,
        public array $middleware = [],
        public array $withoutMiddleware = [],
        public ?string $domain = null,
        public array $wheres = [],
        public ?bool $scopeBindings = null,
    ) {
        //
    }
}
