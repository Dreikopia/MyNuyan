<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AdminSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SessionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [SessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [SessionController::class, 'store']);
});

Route::prefix('admin')
    ->name('admin.')
    ->middleware('guest:admin')
    ->group(function () {
        Route::get('/login', [AdminSessionController::class, 'create'])
            ->name('login');
        Route::post('/login', [AdminSessionController::class, 'store']);

    });

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth:admin')
    ->group(function () {
        Route::delete('/logout', [AdminSessionController::class, 'destroy'])
            ->name('logout');
    });

Route::middleware('auth')
    ->group(function () {
        Route::post('/logout', [SessionController::class, 'destroy'])
            ->name('logout');
    });
