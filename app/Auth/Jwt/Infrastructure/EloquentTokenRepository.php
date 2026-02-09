<?php

namespace App\Auth\Jwt\Infrastructure;

use App\Auth\Jwt\Contracts\TokenRepository;
use App\Models\JwtToken;
use Cache;
use Carbon\Carbon;

class EloquentTokenRepository implements TokenRepository
{
    public function save(string $jti, int $userId, string $type, \DateTimeInterface $expiresAt): void
    {
        JwtToken::create(
            [
                'key'        => $jti,
                'user_id'    => $userId,
                'type'       => $type,
                'expired_at' => Carbon::instance($expiresAt),
            ],
        );
    }

    public function revokeByJti(string $jti): void
    {
        Cache::put($jti, true, now()->addMinutes(1));

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
        if (Cache::has($jti)) {
            return Cache::get($jti);
        }

        $exists = JwtToken::where('key', $jti)
            ->where('expired_at', '<=', now())
            ->exists();

        Cache::add($jti, $exists, now()->addMinutes(1));

        return $exists;
    }
}
