<?php
USE App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('users', \App\Http\Controllers\UserController::class);
