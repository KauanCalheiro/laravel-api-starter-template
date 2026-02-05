<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JwtToken extends Model
{
    protected $table = 'jwt_tokens';

    protected $fillable = [
        'key',
        'value',
        'user_id',
        'type',
        'expired_at',
    ];

    public static function invalidadeUserTokens(User $user): void
    {
        JwtToken::where('user_id', $user->id)
            ->update([
                'value'      => 'forever',
                'expired_at' => now(),
            ]);
    }
}
