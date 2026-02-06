<?php

namespace App\Auth\Jwt\Contracts;

interface TokenRepository
{
    public function save(string $jti, int $userId, string $type, \DateTimeInterface $expiresAt): void;

    public function revokeByJti(string $jti): void;

    public function revokeByUser(int $userId): void;

    public function isRevoked(string $jti): bool;
}
