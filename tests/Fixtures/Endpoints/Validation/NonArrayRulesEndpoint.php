<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Validation;

use Jasonej\LaravelEndpoints\Endpoint;

/**
 * Has no route attribute: it is resolved directly from the container so the
 * test can assert the RuntimeException raised when rules() does not return an
 * array.
 */
final class NonArrayRulesEndpoint extends Endpoint
{
    public function rules(): string
    {
        return 'not-an-array';
    }

    public function __invoke(): string
    {
        return 'ok';
    }
}
