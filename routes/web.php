<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
