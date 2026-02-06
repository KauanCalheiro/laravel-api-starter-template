<?php

namespace App\Providers\Storage;

use App\Auth\Jwt\Contracts\TokenRepository;
use Tymon\JWTAuth\Contracts\Providers\Storage;

class JwtBlacklistStorageProvider implements Storage
{
    public function __construct(
        protected readonly TokenRepository $tokenRepository,
    ) {
    }

    /**
     * Add a new item into storage.
     *
     * @param  string $key
     * @param  mixed  $value
     * @param  int    $minutes
     * @return void
    */
    public function add($key, $value, $minutes)
    {
        $this->tokenRepository->revokeByJti($key);
    }

    /**
     * Add a new item into storage forever.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
    */
    public function forever($key, $value)
    {
        $this->add($key, $value, 0);
    }

    /**
     * Get an item from storage.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->tokenRepository->isRevoked($key) ? 'forever' : null;
    }

    /**
     * Remove an item from storage.
     *
     * @param  string  $key
     * @return bool
     */
    public function destroy($key)
    {
        $this->tokenRepository->revokeByJti($key);

        return true;
    }

    /**
     * Remove all items associated with the tag.
     *
     * @return void
     */
    public function flush()
    {
    }
}
