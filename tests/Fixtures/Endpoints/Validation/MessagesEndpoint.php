<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Validation;

use Jasonej\LaravelEndpoints\Attributes\Post;
use Jasonej\LaravelEndpoints\Endpoint;

#[Post('/messages', name: 'endpoints.messages')]
final class MessagesEndpoint extends Endpoint
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The custom name message.',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => 'email address',
        ];
    }

    public function __invoke(): string
    {
        return 'ok';
    }
}
