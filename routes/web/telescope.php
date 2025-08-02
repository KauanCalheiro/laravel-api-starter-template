<?php

use App\Http\Controllers\Web\TelescopeController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/telescope-login', [TelescopeController::class, 'renderLogin'])->name('telescope.login.get');

    Route::post('/telescope-login', [TelescopeController::class,'authenticate'])->name('telescope.login.post');
});
