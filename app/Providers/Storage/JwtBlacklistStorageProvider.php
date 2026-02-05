<?php

namespace App\Providers\Storage;

use App\Models\JwtToken;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Storage;

class JwtBlacklistStorageProvider implements Storage
{
    /**
    * The JWT jwtToken model instance.
    *
    * @var JwtToken
    */
    protected $jwtToken;

    /**
     * Constructor.
     *
     * @param JwtToken $jwtToken
     */
    public function __construct(JwtToken $jwtToken)
    {
        $this->jwtToken = $jwtToken;
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
        $this->jwtToken->updateOrInsert(
            ['key' => $key],
            [
                'value'      => $value,
                'expired_at' => now(),
            ],
        );
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
        return $this->jwtToken->where('key', $key)
        ->where('expired_at', '<', now())
        ->first()?->value;
    }

    /**
     * Remove an item from storage.
     *
     * @param  string  $key
     * @return bool
     */
    public function destroy($key)
    {
        return $this->jwtToken->where('key', $key)->delete();
    }

    /**
     * Remove all items associated with the tag.
     *
     * @return void
     */
    public function flush()
    {
        $this->jwtToken->truncate();
    }
}
