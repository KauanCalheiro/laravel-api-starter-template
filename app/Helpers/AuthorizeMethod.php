<?php

namespace App\Helpers;

use InvalidArgumentException;

class AuthorizeMethod
{
    public string $method;
    public string $permission;
    public string $param;

    public function __construct(...$args)
    {
        $this->resolveConstructorArgs($args);
        $this->validate();
    }

    protected function resolveConstructorArgs(array $args): void
    {
        $count = count($args);

        match ($count) {
            2       => $this->setWithTwoArgs($args[0], $args[1]),
            3       => $this->setWithThreeArgs($args[0], $args[1], $args[2]),
            default => throw new InvalidArgumentException(__(
                'validation.custom.args_count',
                ['min' => 2, 'max' => 3, 'example_min' => 'method, param', 'example_max' => 'method, permission, param'],
            )),
        };
    }

    protected function setWithTwoArgs(string $method, string $param): void
    {
        $this->method     = $method;
        $this->permission = $method;
        $this->param      = $param;
    }

    protected function setWithThreeArgs(string $method, string $permission, string $param): void
    {
        $this->method     = $method;
        $this->permission = $permission;
        $this->param      = $param;
    }

    protected function validate(): void
    {
        foreach (['method', 'permission', 'param'] as $attribute) {
            if (empty($this->$attribute)) {
                throw new InvalidArgumentException(__(
                    'validation.required',
                    ['attribute' => __($attribute)],
                ));
            }
        }
    }
}
