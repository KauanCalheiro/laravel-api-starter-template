<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Pais;
use Illuminate\Foundation\Http\FormRequest;

class StoreEstadoRequest extends FormRequest
{
    protected Pais $pais;

    public function __construct()
    {
        $this->pais = new Pais();
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'nome'  => mb_convert_case($this->nome, MB_CASE_UPPER),
            'sigla' => mb_convert_case($this->sigla, MB_CASE_UPPER),
        ]);
    }

    public function rules(): array
    {
        return [
            'nome'     => ['required', 'string', 'max:255', 'uppercase'],
            'sigla'    => ['required', 'string', 'max:2', 'uppercase'],
            'ref_pais' => [
                'required',
                'integer',
                "exists:{$this->pais->getTable()},{$this->pais->getKeyName()}",
            ],
        ];
    }
}
