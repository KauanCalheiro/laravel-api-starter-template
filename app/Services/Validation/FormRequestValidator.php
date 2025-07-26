<?php

namespace App\Services\Validation;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class FormRequestValidator
{
    private FormRequest $request;
    private ValidatorContract $validator;

    public function __construct(FormRequest $request, array $data)
    {
        $this->request = $request;
        $this->authorize();
        $this->validator = Validator::make(
            $data,
            $request->rules(),
            $request->messages(),
            $request->attributes(),
        );
    }

    private function authorize(): void
    {
        if (method_exists($this->request, 'authorize') && ! $this->request->authorize()) {
            throw new AuthorizationException(__('authorization.unauthorized'));
        }
    }

    public static function make(FormRequest $request, array $data): self
    {
        return new self($request, $data);
    }

    public function validate(): array
    {
        return $this->validator->validate();
    }
}
