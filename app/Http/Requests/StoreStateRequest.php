<?php

namespace App\Http\Requests;

use App\Models\Location\Country;
use Illuminate\Foundation\Http\FormRequest;

class StoreStateRequest extends FormRequest
{
    protected Country $country;

    public function __construct()
    {
        $this->country = new Country();
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => mb_convert_case($this->name, MB_CASE_UPPER),
            'code' => mb_convert_case($this->code, MB_CASE_UPPER),
        ]);
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255', 'uppercase'],
            'code'       => ['required', 'string', 'max:2', 'uppercase'],
            'country_id' => [
                'required',
                'integer',
                "exists:{$this->country->getTable()},{$this->country->getKeyName()}",
            ],
        ];
    }
}
