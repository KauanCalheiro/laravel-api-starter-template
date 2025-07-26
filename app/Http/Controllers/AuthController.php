<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\V1\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        return AuthService::make($credentials)->handleLogin();
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return [
            'id'          => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'roles'       => $user->roles_list,
            'permissions' => $user->permissions_list,
        ];
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => __('auth.logout.success')], 200);
    }
}
