<?php

namespace App\Models;

use App\Traits\HasActiveRole;
use App\Traits\JwtImpersonate;
use App\Traits\LogsAll;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use LogsAll;
    use HasActiveRole;
    use SoftDeletes;
    use JwtImpersonate;

    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $this->shouldHash($value) ? bcrypt($value) : $value,
        );
    }

    protected function shouldHash($value): bool
    {
        return !password_get_info($value)['algo'];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function canImpersonate(): bool
    {
        return true;
    }

    public function canBeImpersonated(): bool
    {
        return true;
    }
}
