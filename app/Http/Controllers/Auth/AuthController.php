<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActiveRoleRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LogoutRequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\JwtTokenResource;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\ActiveRoleResolver;
use App\Services\Auth\JwtAuthService;
use App\Services\Validation\FormRequestFactory;
use Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        return AuthService::make($request->validated())->login();
    }

    public function me()
    {
        return new AuthUserResource(Auth::user());
    }

    public function logout(LogoutRequest $request)
    {
        AuthService::make($request->validated())->logout();

        return ['message' => __('auth.logout.success')];
    }

    public function refresh(RefreshTokenRequest $request)
    {
        return AuthService::make($request->validated())->refresh();
    }

    public function register(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {
            User::create($request->validated());

            $loginRequest = FormRequestFactory::make(LoginRequest::class, $request->validated());

            return AuthService::make($loginRequest->validated())->login();
        });
    }

    public function activeRole(ActiveRoleRequest $request)
    {
        $user = Auth::user()->loadMissing('roles');

        $activeRole = app(ActiveRoleResolver::class)->resolve($user, $request->string('role')->toString());

        return JwtAuthService::guard()->issueTokens($user, ['active_role' => $activeRole]);
    }

    public function impersonate(User $user)
    {
        return new JwtTokenResource(auth()->user()->impersonate($user));
    }

    public function unimpersonate()
    {
        return new JwtTokenResource(auth()->user()->leaveImpersonation());
    }
}
