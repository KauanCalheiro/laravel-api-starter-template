<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');

        Route::middleware('auth:api')->group(function () {
            Route::get('me', [AuthController::class, 'me'])->name('auth.me');
            Route::get('impersonate/take/{user}', [AuthController::class,'impersonate'])->name('auth.impersonate');
            Route::get('impersonate/leave', [AuthController::class,'unimpersonate'])->name('auth.unimpersonate');
            Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::post('active-role', [AuthController::class, 'activeRole'])->name('auth.active-role');
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::apiResource('user', UserController::class)->names('user');

        Route::post('user/{user}/roles/assign', [UserController::class, 'assignRoles'])->name('user.roles.assign');
        Route::post('user/{user}/roles/revoke', [UserController::class, 'revokeRoles'])->name('user.roles.revoke');
        Route::post('user/{user}/roles/sync', [UserController::class, 'syncRoles'])->name('user.roles.sync');
    });
});
