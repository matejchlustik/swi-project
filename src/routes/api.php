<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmailVerificationController;

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


//Routes accesible only with token
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/email/verify',[EmailVerificationController::class, "notice"])->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, "verify"])->middleware('signed')->name('verification.verify');

    Route::get('/email/verification-notification', [EmailVerificationController::class, "resend"])->name('verification.send');

    Route::post("/logout", [UserController::class, "logout"]);

    //Routes accesible only if email is verified
    Route::group(['middleware' => ['verified']], function () {

        Route::post("/users", [UserController::class, "store"])->middleware(
            'ability:create-department-head,create-department-employee,create-company-representative,create-student'
        );

    });

});
