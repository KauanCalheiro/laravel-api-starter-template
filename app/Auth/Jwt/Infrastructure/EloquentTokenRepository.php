<?php

namespace App\Auth\Jwt\Infrastructure;

use App\Auth\Jwt\Contracts\TokenRepository;
use App\Models\JwtToken;
use Carbon\Carbon;

class EloquentTokenRepository implements TokenRepository
{
    private array $isRevokedStory = [];

    public function save(string $jti, int $userId, string $type, \DateTimeInterface $expiresAt): void
    {
        $this->isRevokedStory[$jti] = true;

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
        $this->isRevokedStory[$jti] = true;

        JwtToken::where('key', $jti)->update([
            'expired_at' => now(),
            'value'      => 'forever',
        ]);
    }

    public function revokeByUser(int $userId): void
    {
        $this->isRevokedStory[$userId] = true;

        JwtToken::where('user_id', $userId)->update([
            'expired_at' => now(),
            'value'      => 'forever',
        ]);
    }

    public function isRevoked(string $jti): bool
    {
        if (array_key_exists($jti, $this->isRevokedStory)) {
            return $this->isRevokedStory[$jti];
        }

        $exists = JwtToken::where('key', $jti)
            ->where('expired_at', '<=', now())
            ->exists();

        $this->isRevokedStory[$jti] = $exists;

        return $exists;
    }
}
