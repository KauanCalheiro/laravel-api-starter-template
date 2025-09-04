<?php

use App\Http\Controllers\Web\ScalarController;

Route::prefix('docs/api')->group(function () {
    Route::get('/', [ScalarController::class, 'index'])->name('docs.scalar.index');
    Route::get('/spec', [ScalarController::class, 'spec'])->name('docs.scalar.spec');
});
