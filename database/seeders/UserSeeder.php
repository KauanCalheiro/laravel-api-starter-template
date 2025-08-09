<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('admin'),
        ]);

        $admin->assignRole([
            RoleEnum::ADMIN->value,
            RoleEnum::USER->value,
        ]);
    }
}
