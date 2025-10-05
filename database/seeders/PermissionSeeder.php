<?php

namespace Database\Seeders;

use App\Services\Permission\PermissionSyncerService;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        PermissionSyncerService::sync();
    }
}
