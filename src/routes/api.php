<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\PracticeRecordsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("/register", [UserController::class, "register"]);

Route::post("/login", [UserController::class, "login"]);

Route::put("/companies/{company}", [CompanyController::class, "update"]);

Route::delete("/companies/{company}", [CompanyController::class, "destroy"]);

Route::get("/companies", [CompanyController::class, "index"]);

Route::get("/companies/{company}", [CompanyController::class, "show"]);

Route::post("/companies", [CompanyController::class, "store"]);

Route::post('/forgot-password', [UserController::class, "forgotPassword"])->name('password.email');

Route::get('/reset-password/{token}', [UserController::class, "resetToken"])->name('password.reset');

Route::post('/reset-password', [UserController::class, "passwordReset"])->name('password.update');

//Routes accesible only with token
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/email/verify',[EmailVerificationController::class, "notice"])->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, "verify"])->middleware('signed')->name('verification.verify');

    Route::get('/email/verification-notification', [EmailVerificationController::class, "resend"])->name('verification.send');

    Route::post("/logout", [UserController::class, "logout"]);

    Route::post('/change-password', [UserController::class, "changePassword"]);

    //Routes accesible only if email is verified
    Route::group(['middleware' => ['verified']], function () {

        Route::post("/users", [UserController::class, "store"])->middleware(
            'ability:create-department-head,create-department-employee,create-company-representative,create-student'
        );

        Route::group(['middleware' => ["ability:read-practices"]], function () {

            Route::get('/practice_records/practices/{practice}', [PracticeRecordsController::class, "index"]);

            Route::get('/practices', [PracticeController::class, "index"]);

            Route::get('/practices/{practice}', [PracticeController::class, "show"]);
        });
        Route::group(['middleware' => ["ability:manage-practices"]], function () {

            Route::post('/practice_records', [PracticeRecordsController::class, "store"]);

            Route::put('/practice_records/{practice_record}', [PracticeRecordsController::class, "update"]);

            Route::delete('/practice_records/{practice_record}', [PracticeRecordsController::class, "destroy"]);

            Route::post('/practices', [PracticeController::class, "store"]);

            Route::put('/practices/{practice}', [PracticeController::class, "update"]);

            Route::delete('/practices/{practice}', [PracticeController::class, "destroy"]);

            Route::post('/practices_contract/{id}', [PracticeController::class, "update_contract"]);
        });

    });

});
