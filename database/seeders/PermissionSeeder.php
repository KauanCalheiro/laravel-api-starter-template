<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Models\Auth\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        collect(PermissionEnum::cases())->each(function ($permission) {
            Permission::create([
                'name' => $permission->value,
            ]);
        });
    }
}
