<?php

namespace App\Models;

use App\Enums\ConnectionEnum;
use App\Traits\HasRoles;
use App\Traits\RoleAssignmentRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use ReadOnlyTrait;
    use RoleAssignmentRules;

    protected $table = 'users';

    protected $connection = ConnectionEnum::MAIN;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
    ];

    /**
     * Accessor for the user's roles.
     */
    public function getRolesListAttribute(): array
    {
        return $this->roles->pluck('name')->toArray();
    }

    /**
     * Accessor for the user's permissions.
     */
    public function getPermissionsListAttribute(): array
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }
}
