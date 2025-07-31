<?php

namespace App\Http\Requests;

use App\Models\Location\State;
use Illuminate\Foundation\Http\FormRequest;

class StoreCityRequest extends FormRequest
{
    protected State $state;

    public function __construct()
    {
        $this->state = new State();
    }

    public function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge(['name' => mb_strtoupper($this->name)]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'state_id' => [
                'required',
                "exists:{$this->state->getTable()},{$this->state->getKeyName()}",
            ],
        ];
    }
}
