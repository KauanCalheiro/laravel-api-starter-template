<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JwtTokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->resource,
            'token_type'   => 'Bearer',
            'expires_in'   => (config('jwt.ttl') * 60),
        ];
    }
}
