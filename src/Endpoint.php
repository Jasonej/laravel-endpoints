<?php

declare(strict_types=1);

namespace Jasonej\LaravelEndpoints;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Precognition;
use Illuminate\Support\Traits\InteractsWithData;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Override;
use RuntimeException;

abstract class Endpoint implements ValidatesWhenResolved
{
    use InteractsWithData;
    use ValidatesWhenResolvedTrait;

    /**
     * @return array<array-key, mixed>
     */
    #[Override]
    public function all(mixed $keys = null): array
    {
        return request()->all($keys);
    }

    #[Override]
    public function validateResolved(): void
    {
        $this->prepareForValidation();

        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = $this->getValidatorInstance();

        if (request()->isPrecognitive()) {
            $instance->after(Precognition::afterValidationHook(request()));
        }

        if ($instance->fails()) {
            $this->failedValidation($instance);
        }

        $this->passedValidation();
    }

    #[Override]
    protected function data(mixed $key = null, mixed $default = null): mixed
    {
        return request()->input($key, $default);
    }

    protected function passesAuthorization(): bool
    {
        if (method_exists($this, 'authorize')) {
            $result = app()->call($this->authorize(...));

            if (!is_bool($result)) {
                throw new RuntimeException(sprintf(
                    '%s::authorize() must return a boolean.',
                    static::class,
                ));
            }

            return $result;
        }

        return true;
    }

    protected function validator(): Validator
    {
        return resolve(Factory::class)->make(
            data: $this->all(),
            rules: $this->getValidatorConfig('rules'),
            messages: $this->getValidatorConfig('messages'),
            attributes: $this->getValidatorConfig('attributes'),
        );
    }

    /**
     * @return array<array-key, mixed>
     */
    private function getValidatorConfig(string $key): array
    {
        if (!method_exists($this, $key)) {
            return [];
        }

        $result = app()->call($this->{$key}(...));

        if (!is_array($result)) {
            throw new RuntimeException(sprintf(
                'The %s::%s method must return an array.',
                static::class,
                $key,
            ));
        }

        return $result;
    }
}
