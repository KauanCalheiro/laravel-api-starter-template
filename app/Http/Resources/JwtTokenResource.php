<?php

namespace App\Http\Resources;

use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JwtTokenResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'access_token'  => $this->access_token,
            'refresh_token' => $this->refresh_token,
            'token_type'    => 'Bearer',
            'expires_in'    => AuthService::authResolver()->getTTL() * 60,
        ];
    }
}
