<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Feature;

use Illuminate\Validation\ValidationException;
use Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Validation\DependencyRulesEndpoint;
use Jasonej\LaravelEndpoints\Tests\Fixtures\Endpoints\Validation\NonArrayRulesEndpoint;
use Jasonej\LaravelEndpoints\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;

final class EndpointValidationTest extends TestCase
{
    #[Test]
    public function aRequestThatSatisfiesTheRulesReachesTheHandler(): void
    {
        $this->registerEndpointsIn('Validation');

        $this->postJson('/validating', ['name' => 'Ada'])
            ->assertOk()
            ->assertExactJson(['name' => 'Ada']);
    }

    #[Test]
    public function aRequestThatViolatesTheRulesFailsValidation(): void
    {
        $this->registerEndpointsIn('Validation');

        $this->postJson('/validating', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    #[Test]
    public function anEndpointWithoutValidationMethodsReachesTheHandler(): void
    {
        $this->registerEndpointsIn('Simple');

        $this->get('/simple')
            ->assertOk()
            ->assertSee('ok');
    }

    #[Test]
    public function customMessagesAndAttributesAreAppliedToTheValidator(): void
    {
        $this->registerEndpointsIn('Validation');

        $response = $this->postJson('/messages', [])->assertStatus(422);

        $response->assertJsonPath('errors.name.0', 'The custom name message.');
        $this->assertStringContainsString(
            'email address',
            $response->json('errors.email.0'),
        );
    }

    #[Test]
    public function configMethodsAreResolvedThroughTheContainer(): void
    {
        $this->expectException(ValidationException::class);

        // Resolving the endpoint triggers ValidatesWhenResolved; rules() receives
        // a container-resolved RuleProvider, so an empty request fails validation.
        $this->app->make(DependencyRulesEndpoint::class);
    }

    #[Test]
    public function aConfigMethodThatReturnsANonArrayThrows(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('must return an array');

        $this->app->make(NonArrayRulesEndpoint::class);
    }
}
