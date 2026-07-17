<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Validation;

use Jasonej\LaravelEndpoints\Endpoint;
use Jasonej\LaravelEndpoints\Tests\Fixtures\RuleProvider;

/**
 * Has no route attribute: it is resolved directly from the container so the
 * test can assert that rules() receives a container-resolved dependency.
 */
final class DependencyRulesEndpoint extends Endpoint
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(RuleProvider $provider): array
    {
        return $provider->rules();
    }

    public function __invoke(): string
    {
        return 'ok';
    }
}
