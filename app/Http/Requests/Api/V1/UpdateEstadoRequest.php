<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Pais;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEstadoRequest extends FormRequest
{
    protected Pais $pais;

    public function __construct()
    {
        $this->pais = new Pais();
    }

    public function prepareForValidation(): void
    {
        foreach (['nome', 'sigla'] as $field) {
            if ($this->has($field)) {
                $this->merge([$field => mb_convert_case($this->{$field}, MB_CASE_UPPER)]);
            }
        }
    }
    public function rules(): array
    {
        return [
            'nome'     => ['sometimes', 'required', 'string', 'max:255', 'uppercase'],
            'sigla'    => ['sometimes', 'required', 'string', 'max:2', 'uppercase'],
            'ref_pais' => [
                'sometimes',
                'required',
                'integer',
                "exists:{$this->pais->getTable()},{$this->pais->getKeyName()}",
            ],
        ];
    }
}
