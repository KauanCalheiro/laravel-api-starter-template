<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Validation\FormRequestFactory;
use DB;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        return AuthService::make($request->validated())->handleLogin();
    }

    public function user(Request $request)
    {
        return new AuthUserResource($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

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
}
