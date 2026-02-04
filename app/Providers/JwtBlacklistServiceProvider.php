<?php

namespace App\Providers;

use App\Providers\Storage\JwtBlacklistStorageProvider;
use Illuminate\Support\ServiceProvider;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Storage;

class JwtBlacklistServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Storage::class, JwtBlacklistStorageProvider::class);
    }
}
