<?php

use App\Models\Auth\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    protected User $user;
    protected Role $role;

    public function __construct()
    {
        $this->user = new User();
        $this->role = new Role();
    }

    public function up(): void
    {
        Schema::create('user_active_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained($this->user->getTable());
            $table->foreignId('role_id')->constrained($this->role->getTable());
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_active_role');
    }
};
