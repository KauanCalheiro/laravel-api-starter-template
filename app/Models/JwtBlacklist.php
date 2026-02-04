<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JwtBlacklist extends Model
{
    protected $table = 'jwt_blacklists';

    protected $fillable = [
        'key',
        'value',
        'expired_at',
    ];
}
