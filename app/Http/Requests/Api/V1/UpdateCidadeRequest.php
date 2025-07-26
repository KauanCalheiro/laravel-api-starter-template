<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Estado;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCidadeRequest extends FormRequest
{
    protected Estado $estado;

    public function __construct()
    {
        $this->estado = new Estado();
    }

    public function prepareForValidation(): void
    {
        foreach (['nome'] as $field) {
            if ($this->has($field)) {
                $this->merge([$field => mb_convert_case($this->{$field}, MB_CASE_UPPER)]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'nome' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],
            'ref_estado' => [
                'sometimes',
                'required',
                "exists:{$this->estado->getTable()},{$this->estado->getKeyName()}",
            ],
        ];
    }
}
