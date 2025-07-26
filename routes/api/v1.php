<?php

use App\Http\Controllers\AcaoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\CurriculoController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\EvidenciaController;
use App\Http\Controllers\OdsController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\PlanejamentoAcaoController;
use App\Http\Controllers\ProgramaExtensaoController;
use App\Http\Controllers\ProjetoExtensaoController;
use App\Http\Controllers\TurmaExtensaoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login'])->name('auth.login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('user', [AuthController::class, 'user'])->name('auth.user');
            Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('sync')->group(function () {
            Route::get('user', [UserController::class, 'sync'])->name('sync.user');
            Route::get('turma-extensao', [TurmaExtensaoController::class, 'sync'])->name('sync.turma-extensao');
        });

        Route::apiResource('pais', PaisController::class)
            ->parameters(['pais' => 'pais'])
            ->names('pais');

        Route::apiResource('estado', EstadoController::class)
            ->names('estado');

        Route::apiResource('cidade', CidadeController::class)
            ->names('cidade');

        Route::apiResource('user', UserController::class)
            ->only(['index', 'show']);

        Route::post('user/{user}/sync-role', [UserController::class, 'syncRoles'])->name('user.sync-roles');

        Route::apiResource('curriculo', CurriculoController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'curriculo.index',
                'show'  => 'curriculo.show',
            ]);

        Route::apiResource('disciplina', DisciplinaController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'disciplina.index',
                'show'  => 'disciplina.show',
            ]);

        Route::apiResource('turma-extensao', TurmaExtensaoController::class)
            ->only(['index', 'show'])
            ->names([
                'index' => 'turma-extensao.index',
                'show'  => 'turma-extensao.show',
            ]);

        Route::apiResource('projeto-extensao', ProjetoExtensaoController::class)
            ->names('projeto-extensao');

        Route::apiResource('programa-extensao', ProgramaExtensaoController::class)
            ->names('programa-extensao');

        Route::apiResource('planejamento-acao', PlanejamentoAcaoController::class)->names('planejamento-acao');

        Route::apiResource('acao', AcaoController::class)
            ->names('acao');

        Route::apiResource('ods', OdsController::class)
            ->parameters(['ods' => 'ods'])
            ->names('ods');

        Route::apiResource('evidencia', EvidenciaController::class)
            ->parameters(['evidencia' => 'evidencia'])
            ->names('evidencia');

        Route::patch('evidencia/{evidencia}/feedback', [EvidenciaController::class, 'feedback'])
            ->name('evidencia.feedback');
    });

    Route::post('send-mail', [EmailController::class, 'send']);
});
