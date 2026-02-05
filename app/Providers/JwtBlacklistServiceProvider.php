<?php

namespace App\Providers;

use App\Providers\Storage\JwtBlacklistStorageProvider;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Contracts\Providers\Storage;

class JwtBlacklistServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Storage::class, JwtBlacklistStorageProvider::class);
    }
}
