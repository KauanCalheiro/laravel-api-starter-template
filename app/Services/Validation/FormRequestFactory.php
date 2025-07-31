<?php

namespace App\Services\Validation;

use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;

class FormRequestFactory
{
    /**
     * Cria e valida um FormRequest a partir de:
     * - string: nome da classe FormRequest
     * - instância de FormRequest
     *
     * @param  string|FormRequest  $requestOrClass
     * @param  array               $data
     * @return FormRequest
     */
    public static function make(string|FormRequest $requestOrClass, array $data = []): FormRequest
    {
        $request = self::resolveRequest($requestOrClass);

        $request->replace($data);
        $request->setContainer(app());
        $request->validateResolved();

        return $request;
    }

    /**
     * Resolve a instância de FormRequest a partir da classe ou da instância.
     *
     * @param  string|FormRequest  $requestOrClass
     * @return FormRequest
     */
    private static function resolveRequest(string|FormRequest $requestOrClass): FormRequest
    {
        if (is_string($requestOrClass)) {
            /** @var FormRequest $request */
            return $requestOrClass::createFromBase(request());
        }

        if ($requestOrClass instanceof FormRequest) {
            return $requestOrClass;
        }

        throw new InvalidArgumentException('Parameter must be a FormRequest instance or a class name string.');
    }
}
