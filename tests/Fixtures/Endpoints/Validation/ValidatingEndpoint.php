<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Validation;

use Jasonej\LaravelEndpoints\Attributes\Post;
use Jasonej\LaravelEndpoints\Endpoint;

#[Post('/validating', name: 'endpoints.validating')]
final class ValidatingEndpoint extends Endpoint
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

    /**
     * @return array<string, mixed>
     */
    public function __invoke(): array
    {
        return ['name' => $this->data('name')];
    }
}
