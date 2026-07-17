<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints\Tests\Feature;

use Jasonej\LaravelEndpoints\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class EndpointScopeBindingsTest extends TestCase
{
    #[Test]
    public function scopeBindingsTrueEnforcesScopedBindings(): void
    {
        $this->registerEndpointsIn('ScopeBindings');

        $route = $this->route('endpoints.scope.true');

        $this->assertNotNull($route);
        $this->assertTrue($route->enforcesScopedBindings());
        $this->assertFalse($route->preventsScopedBindings());
    }

    #[Test]
    public function scopeBindingsFalsePreventsScopedBindings(): void
    {
        $this->registerEndpointsIn('ScopeBindings');

        $route = $this->route('endpoints.scope.false');

        $this->assertNotNull($route);
        $this->assertTrue($route->preventsScopedBindings());
        $this->assertFalse($route->enforcesScopedBindings());
    }

    #[Test]
    public function scopeBindingsNullLeavesTheRouteUntouched(): void
    {
        $this->registerEndpointsIn('ScopeBindings');

        $route = $this->route('endpoints.scope.default');

        $this->assertNotNull($route);
        $this->assertFalse($route->enforcesScopedBindings());
        $this->assertFalse($route->preventsScopedBindings());
    }
}
