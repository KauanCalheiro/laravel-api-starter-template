<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActiveRoleRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\Auth\Role;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Validation\FormRequestFactory;
use Auth;
use DB;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $token = AuthService::make($request->validated())->handleLogin();

            Auth::user()->setActiveRole();

            return $token;
        });
    }

    public function user()
    {
        return new AuthUserResource(Auth::user());
    }

    public function logout()
    {
        Auth::logout();

        return ['message' => __('auth.logout.success')];
    }

    public function register(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {
            User::create($request->validated());

            $loginRequest = FormRequestFactory::make(LoginRequest::class, $request->validated());

            return AuthService::make($loginRequest->validated())->handleLogin();
        });
    }

    public function activeRole(ActiveRoleRequest $request)
    {
        $role = Role::where('name', $request->input('role'))->firstOrFail();

        Auth::user()->setActiveRole($role);

        return $this->user();
    }

    public function impersonate(User $user)
    {
        return ['token' => Auth::user()->impersonate($user)];
    }

    public function unimpersonate()
    {
        return ['token' => Auth::user()->leaveImpersonation()];
    }
}
