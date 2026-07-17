# Laravel Endpoints

Single file endpoints for Laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jasonej/laravel-endpoints.svg?style=flat-square)](https://packagist.org/packages/jasonej/laravel-endpoints)
[![Total Downloads](https://img.shields.io/packagist/dt/jasonej/laravel-endpoints.svg?style=flat-square)](https://packagist.org/packages/jasonej/laravel-endpoints)
[![Tests](https://github.com/jasonej/laravel-endpoints/actions/workflows/tests.yml/badge.svg)](https://github.com/jasonej/laravel-endpoints/actions/workflows/tests.yml)

> A typical Laravel endpoint is spread across a route, a form request, and a controller. `laravel-endpoints` collapses
> all three into a single auto-discovered class: extend `Endpoint`, declare the route with an attribute, and handle the
> request in `__invoke`. Add `rules()` to validate and `authorize()` to gate access, just like a form request.

```php
#[Post('/api/comments', middleware: ['api', 'auth:sanctum'])]
final class CreateCommentEndpoint extends Endpoint
{
    public function authorize(): bool
    {
        return Gate::allows('create', Comment::class);
    }
    
    public function rules(): array
    {
        return [
            'body' => ['required', 'string'],
        ];
    }

    public function __invoke()
    {
        // ...
    }
}
```

## Installation

You can install the package via composer:
```shell
composer require jasonej/laravel-endpoints
```

You can publish the config file with:
```shell
php artisan vendor:publish --tag="endpoints-config"
```

## Usage

### Defining an endpoint

Extend `Endpoint`, declare a route with an attribute, and handle the request in `__invoke`. No need to register it 
in `routes/api.php` as endpoints are auto-discovered.

```php
<?php

declare(strict_types=1);

namespace App\Endpoints;

use Jasonej\LaravelEndpoints\Attributes\Get;
use Jasonej\LaravelEndpoints\Endpoint;

#[Get('/status')]
final class StatusEndpoint extends Endpoint
{
    public function __invoke(): array
    {
        return ['status' => 'ok'];
    }
}
```

### HTTP method attributes

There is one attribute per verb (`#[Get]`, `#[Post]`, `#[Put]`, `#[Patch]`, `#[Delete]`, and `#[Options]`) plus a
raw `#[Route]` for an explicit or multi-verb set. Route names accept a string or a `BackedEnum`.

```php
use Jasonej\LaravelEndpoints\Attributes\Post;

#[Post('/posts', name: 'posts.store')]
final class CreatePostEndpoint extends Endpoint { /* … */ }
```

```php
use Jasonej\LaravelEndpoints\Attributes\Route;

#[Route(['GET', 'POST'], '/webhook', name: 'webhook')]
final class WebhookEndpoint extends Endpoint { /* … */ }
```

The attributes are repeatable, so you can point several routes at a single handler:

```php
use Jasonej\LaravelEndpoints\Attributes\Patch;
use Jasonej\LaravelEndpoints\Attributes\Put;

#[Put('/profile', name: 'profile.replace')]
#[Patch('/profile', name: 'profile.update')]
final class UpdateProfileEndpoint extends Endpoint { /* … */ }
```

### Route options

Every attribute forwards the full set of route options:

| Option | Type | Maps to |
| --- | --- | --- |
| `name` | `string\|BackedEnum` | `->name()` |
| `middleware` | `array` | `->middleware()` |
| `withoutMiddleware` | `array` | `->withoutMiddleware()` |
| `wheres` | `array<string, string>` | `->where()` |
| `domain` | `string` | `->domain()` |
| `scopeBindings` | `bool` | `->scopeBindings()` / `->withoutScopedBindings()` |

```php
#[Get(
    '/posts/{post}',
    name: 'posts.show',
    middleware: ['api', 'auth:sanctum'],
    withoutMiddleware: ['throttle'],
    wheres: ['post' => '[0-9]+'],
    domain: 'api.example.com',
    scopeBindings: true,
)]
final class ShowPostEndpoint extends Endpoint { /* … */ }
```

### Handling the request

Read input with the typed accessors (`string()`, `integer()`, `boolean()`, `enum()`, `array()`, `all()`, …), or
collect it into a DTO. Route-model-bound parameters are injected into `__invoke` just like any controller action, and
you can return an array, a resource, or a response.

```php
use App\Http\Resources\PostResource;
use App\Models\Post;

#[Get('/posts/{post}', wheres: ['post' => '[0-9]+'])]
final class ShowPostEndpoint extends Endpoint
{
    public function __invoke(Post $post): PostResource
    {
        return PostResource::make($post);
    }
}
```

```php
#[Post('/posts')]
final class CreatePostEndpoint extends Endpoint
{
    public function __invoke(): PostResource
    {
        $post = Post::create([
            'title' => $this->string('title')->value(),
            'body' => $this->string('body')->value(),
            'published' => $this->boolean('published'),
        ]);

        return PostResource::make($post);
    }
}
```

### Validation and authorization

Both are optional hooks that work just like a form request. Define `rules()` (with `messages()` and `attributes()` to
customize) to validate input, and `authorize()` to gate access. Validation runs when the endpoint is resolved, and a
failing `authorize()` throws a `403`.

```php
use Illuminate\Support\Facades\Gate;
use App\Models\Post;

#[Post('/posts', middleware: ['auth:sanctum'])]
final class CreatePostEndpoint extends Endpoint
{
    public function authorize(): bool
    {
        return Gate::allows('create', Post::class);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'body' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return ['title.max' => 'Keep the title under 120 characters.'];
    }

    public function attributes(): array
    {
        return ['body' => 'post body'];
    }

    public function __invoke(): PostResource { /* … */ }
}
```

### Configuration and discovery

The package scans the paths in `config/endpoints.php` for classes extending `Endpoint`. Publish the config to change
where it looks:

```shell
php artisan vendor:publish --tag="endpoints-config"
```

```php
// config/endpoints.php
return [
    'paths' => [
        app_path(),           // default
        base_path('modules'), // add your own
    ],
];
```

Endpoints can live anywhere under a configured path.

### Route caching in production

Discovery reflects over the filesystem on every request that is not route-cached. In production, cache your routes so
discovery runs once at build time instead of on every request:

```shell
php artisan route:cache
```

## Changelog

Please see [CHANGELOG](./CHANGELOG.md) for more information on what has changed recently.

## Credits

Please see [CONTRIBUTORS](../../contributors) for more information on who contributed to the package.

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
