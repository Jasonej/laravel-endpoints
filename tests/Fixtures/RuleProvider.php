<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures;

/**
 * A concrete dependency resolved out of the container and injected into an
 * endpoint's rules() method, proving the config methods are called through
 * the container (app()->call) rather than invoked directly.
 */
class RuleProvider
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
        ];
    }
}
