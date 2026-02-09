<?php

namespace App\Traits;

use App\Services\Auth\ActiveRoleResolver;

trait HasActiveRole
{
    public function getActiveRoleAttribute(): ?string
    {
        return app(ActiveRoleResolver::class)->resolve($this);
    }
}
