<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Location\CityController;
use App\Http\Controllers\Location\CountryController;
use App\Http\Controllers\Location\StateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');
        Route::post('register', [AuthController::class, 'register'])->name('auth.register');

        Route::middleware('auth:api')->group(function () {
            Route::get('user', [AuthController::class, 'user'])->name('auth.user');
            Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::post('active-role', [AuthController::class, 'activeRole'])->name('auth.active-role');
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::apiResource('user', UserController::class)->names('user');

        Route::post('user/{user}/roles/assign', [UserController::class, 'assignRoles'])->name('user.roles.assign');
        Route::post('user/{user}/roles/revoke', [UserController::class, 'revokeRoles'])->name('user.roles.revoke');
        Route::post('user/{user}/roles/sync', [UserController::class, 'syncRoles'])->name('user.roles.sync');

        Route::apiResource('country', CountryController::class)->names('country');
        Route::apiResource('state', StateController::class)->names('state');
        Route::apiResource('city', CityController::class)->names('city');
    });
});
