<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Auth\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        collect(RoleEnum::cases())->each(function ($role) {
            Role::create([
                'name' => $role->value,
            ]);
        });

        Role::where('name', RoleEnum::ADMIN->value)->first()->syncPermissions(PermissionEnum::cases());
    }
}
