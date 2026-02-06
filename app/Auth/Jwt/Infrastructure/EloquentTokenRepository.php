<?php

namespace App\Auth\Jwt\Infrastructure;

use App\Auth\Jwt\Contracts\TokenRepository;
use App\Models\JwtToken;
use Carbon\Carbon;

class EloquentTokenRepository implements TokenRepository
{
    public function save(string $jti, int $userId, string $type, \DateTimeInterface $expiresAt): void
    {
        JwtToken::updateOrCreate(
            ['key' => $jti],
            [
                'user_id'    => $userId,
                'type'       => $type,
                'expired_at' => Carbon::instance($expiresAt),
            ],
        );
    }

    public function revokeByJti(string $jti): void
    {
        JwtToken::where('key', $jti)->update([
            'expired_at' => now(),
            'value'      => 'forever',
        ]);
    }

    public function revokeByUser(int $userId): void
    {
        JwtToken::where('user_id', $userId)->update([
            'expired_at' => now(),
            'value'      => 'forever',
        ]);
    }

    public function isRevoked(string $jti): bool
    {
        return JwtToken::where('key', $jti)
            ->where('expired_at', '<=', now())
            ->exists();
    }
}
