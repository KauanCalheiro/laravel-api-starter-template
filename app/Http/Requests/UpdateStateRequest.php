<?php

namespace App\Http\Requests;

use App\Models\Location\Country;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStateRequest extends FormRequest
{
    protected Country $country;

    public function __construct()
    {
        $this->country = new Country();
    }

    public function prepareForValidation(): void
    {
        foreach (['name', 'code'] as $field) {
            if ($this->has($field)) {
                $this->merge([$field => mb_convert_case($this->{$field}, MB_CASE_UPPER)]);
            }
        }
    }
    public function rules(): array
    {
        return [
            'name'       => ['sometimes', 'required', 'string', 'max:255', 'uppercase'],
            'code'       => ['sometimes', 'required', 'string', 'max:2', 'uppercase'],
            'country_id' => [
                'sometimes',
                'required',
                'integer',
                "exists:{$this->country->getTable()},{$this->country->getKeyName()}",
            ],
        ];
    }
}
