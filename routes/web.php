<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return redirect("/login");
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index']);

    Route::post('/login', [LoginController::class, 'loginUser']);

    Route::prefix('/register')->group(function () {
        Route::get('/', [RegisterController::class,'index']);

        Route::get('/{role}', [RegisterController::class, 'indexRole'] );

        Route::post('/{role}/insert', [RegisterController::class, 'insertNewUser'] );
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index']);
});
