<?php

namespace App\Providers\Storage;

use App\Models\JwtToken;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Storage;
use Str;

class JwtBlacklistStorageProvider implements Storage
{
    /**
    * The JWT blacklist model instance.
    *
    * @var JwtToken
    */
    protected $blacklist;

    /**
     * Constructor.
     *
     * @param JwtToken $blacklist
     */
    public function __construct(JwtToken $blacklist)
    {
        $this->blacklist = $blacklist;
    }

    /**
     * Add a new item into storage.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int  $minutes
     * @return void
     */
    public function add($key, $value, $minutes)
    {
        $this->blacklist->updateOrInsert(
            [
                'key'   => $key,
                'value' => $this->toString($value),
            ],
            [
                'expired_at' => now(),
            ],
        );
    }

    /**
     * Add a new item into storage forever.
     *
     * @param  string  $key
     * @param  mixed  $value
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
        $value = $this->blacklist->where('key', $key)
        ->where('expired_at', '>', now())
        ->first()?->value;

        if (is_null($value)) {
            return null;
        }

        return $value;
    }

    /**
     * Remove an item from storage.
     *
     * @param  string  $key
     * @return bool
     */
    public function destroy($key)
    {
        return $this->blacklist->where('key', $key)->delete();
    }

    /**
     * Remove all items associated with the tag.
     *
     * @return void
     */
    public function flush()
    {
        $this->blacklist->truncate();
    }

    /**
     * Convert the given value to a string.
     *
     * @param mixed $value
     *
     * @return string
     */
    private function toString($value): string
    {
        return is_string($value) ? $value : json_encode($value);
    }

    /**
     * Convert the given value from a string.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function fromString($value): mixed
    {
        return Str::isJson($value) ? json_decode($value, true) : $value;
    }
}
