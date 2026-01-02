<?php

use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FindController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\PaperSettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
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

    Route::get("/find", [FindController::class,"index"]);

    Route::get('/search-user-lecturer', [UserController::class, 'searchUserLecturer'])->name('api.users.search.lecturer');

    Route::prefix("/settings")->group(function(){
        Route::get("/", [SettingController::class,"index"]);

        Route::post("/update-public-profile", [SettingController::class,"updatePublicProfile"]);

        Route::post("/request-affiliation", [SettingController::class,"requestAffiliation"]);

        Route::post("/update-interests", [SettingController::class,"updateInterests"]);

        Route::post("/update-email", [SettingController::class,"updateEmail"]);

        Route::post("/update-password", [SettingController::class,"updatePassword"]);

        Route::post("/delete-account", [SettingController::class,"deleteAccount"]);
    });

    Route::prefix("/{profileId}")->group(function(){
        Route::get("/papers", [PaperController::class,"indexPapers"]);

        Route::get("/stars", [PaperController::class,"indexStars"]);

        Route::get("/overview", [ProfileController::class,"indexOverview"]);

        Route::get("/followers", [ProfileController::class,"indexFollowers"]);

        Route::get("/researchers", [ProfileController::class,"indexResearchers"]);

        Route::prefix("/paper/{paperId}")->group(function(){
            Route::get("/overview", [PaperController::class,"paperOverview"]);

            Route::get("/workspace", [PaperController::class,"paperWorkspace"]);
            
            Route::get("/lit-review", [PaperController::class, "LitReview"]);

            Route::post("/add-reference", [PaperController::class,"addReference"]);

            Route::post("/save-synthesis", [PaperController::class, "saveSynthesis"]);

            Route::get("/export-bibtex", [PaperController::class, "exportBibtex"]);

            Route::post("/add-theme", [PaperController::class, "addTheme"]);

            Route::post("/remove-theme", [PaperController::class, "removeTheme"]);

            Route::post("/toggle-collaboration", [PaperController::class,"toggleCollaboration"]);
            
            Route::get("/lit-review", [PaperController::class, "paperLitReview"]);
            
            Route::post("/finalize-lit-review", [PaperController::class, "finalizeLitReview"]);

            Route::post("/create-new-project-role", [PaperController::class,"createNewProjectRole"]);

            Route::prefix("/settings")->group(function(){
                Route::get("/", [PaperSettingController::class,"index"]);

                Route::post("/update-paper", [PaperSettingController::class,"updatePaper"]);

                Route::post("/delete-paper", [PaperSettingController::class,"deletePaper"]);
            });

            Route::prefix("/collaborations")->group(function(){
                Route::get("/", [CollaborationController::class,"index"]);
                

                Route::post("/toggle-collaboration", [CollaborationController::class,"toggleCollaboration"]);

                Route::post("/create-new-role", [CollaborationController::class,"createNewRole"]);

                Route::post("/remove-role", [CollaborationController::class,"removeRole"]);

                Route::post('/invite', [CollaborationController::class, 'inviteUser']);

                Route::post('/cancel-invitation', [CollaborationController::class, 'cancelInvitation']);

                Route::post('/clear-invitation-history', [CollaborationController::class, 'clearInvitationHistory']);

                Route::post('/accept-invitation', [CollaborationController::class, 'acceptInvitation']);

                Route::post('/reject-invitation', [CollaborationController::class, 'rejectInvitation']);

                Route::post('/accept-request', [CollaborationController::class, 'acceptRequest']);

                Route::post('/reject-request', [CollaborationController::class, 'rejectRequest']);

                Route::post('/remove-request', [CollaborationController::class, 'removeRequest']);

                Route::post('/clear-request-history', [CollaborationController::class, 'clearRequestHistory']);

                Route::post('/edit-role', [CollaborationController::class, 'editRole']);

                Route::post('/remove-member', [CollaborationController::class, 'removeMember']);

                Route::post('/apply-for-role', [CollaborationController::class, 'applyForRole']);
            });
        });
    });

    Route::prefix("/papers")->group(function () {
        Route::get("/create", [PaperController::class,"indexCreatePaper"]);

        Route::post("/create-new-paper", [PaperController::class,"createNewPaper"]);

        Route::post('/{paperId}/star', [PaperController::class, 'toggleStar']);
    });
});
