<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return redirect("/login");
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name("login");

    Route::post('/login', [LoginController::class, 'loginUser']);

    Route::prefix('/register')->group(function () {
        Route::get('/', [RegisterController::class,'index']);

        Route::get('/{role}', [RegisterController::class, 'indexRole'] );

        Route::post('/{role}/insert', [RegisterController::class, 'insertNewUser'] );
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name("dashboard");

    Route::post("/logout", [LoginController::class,"logoutUser"]);

    Route::prefix("/settings")->group(function(){
        Route::get("/", [SettingController::class,"index"]);

        Route::post("/update-public-profile", [SettingController::class,"updatePublicProfile"]);

        Route::post("/update-email", [SettingController::class,"updateEmail"]);

        Route::post("/update-password", [SettingController::class,"updatePassword"]);

        Route::post("/delete-account", [SettingController::class,"deleteAccount"]);
    });

    Route::prefix("/{profileId}")->group(function(){
        Route::get("/papers", [PaperController::class,"indexPapers"]);
    });

    Route::prefix("/papers")->group(function () {
        Route::get("/create", [PaperController::class,"indexCreatePaper"]);

        Route::post("/create-new-paper", [PaperController::class,"createNewPaper"]);

        Route::post('/{paperId}/star', [PaperController::class, 'toggleStar']);
    });
});
