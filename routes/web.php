<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CreateUserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UpdateUserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function() {
    return view('welcome');
});


Route::middleware(['auth'])->group(function() {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('profile.')->prefix('users')->group(function() {
        Route::get('/', [ProfileController::class, 'index'])->name('index');

        # create user
        Route::get('add', [CreateUserController::class, 'add'])->name('add');
        Route::post('add', [CreateUserController::class, 'create'])->name('create');

        Route::prefix('{user}')->group(function() {
            Route::get('', [ProfileController::class, 'view'])->name('view');

            # update user
            Route::get('edit', [UpdateUserController::class, 'edit'])->name('edit');
            Route::patch('profile', [UpdateUserController::class, 'update'])->name('update');

            # delete user
            Route::delete('/', [UpdateUserController::class, 'destroy'])->name('destroy');

        });
    });

    Route::name('projects.')->prefix('projects')->group(function() {
        Route::get('/', [ProjectController::class, 'index'])->name('index');

        # create project
        Route::get('add', [ProjectController::class, 'add'])->name('add');
        Route::post('add', [ProjectController::class, 'create'])->name('create');

        Route::prefix('{any_project}')->group(function() {
            Route::get('', [ProjectController::class, 'view'])->name('view');

            # update project
            Route::get('edit', [ProjectController::class, 'edit'])->name('edit');
            Route::patch('update', [ProjectController::class, 'update'])->name('update');

            # delete project
            Route::delete('/', [ProjectController::class, 'destroy'])->name('destroy');

        });
    });
});

require __DIR__ . '/auth.php';
