<?php

use App\Http\Controllers\CompanyDepartmentController;
use App\Http\Controllers\PracticeOffersController;
use App\Models\PracticeOffer;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
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
        Route::group(['middleware' => ["ability:manage-practice_offers"]], function () {

            Route::post("/practice_offers", [PracticeOffersController::class, "store"]);

            Route::put("/practice_offers/{practiceOffer}", [PracticeOffersController::class, "update"]);

            Route::delete("/practice_offers/{practiceOffer}", [PracticeOffersController::class, "destroy"]);
        });

        Route::group(['middleware' => ["ability:read-practice_offers"]], function () {

            Route::get("/practice_offers", [PracticeOffersController::class, "index"]);

            Route::get("/practice_offers/{practiceOffer}", [PracticeOffersController::class, "show"]);

            Route::get("/practice_offers/departments/{department}", [PracticeOffersController::class, "showByDepartment"]);

            Route::get("/practice_offers/companies/{company}", [PracticeOffersController::class, "showByCompany"]);

        });
        Route::group(['middleware' => ["ability:manage-company_department"]], function () {
            Route::get("/company_department", [CompanyDepartmentController::class, "index"]);

            Route::post("/company_department/{companyDepartment}", [CompanyDepartmentController::class, "store"]);

            Route::get("/company_department/{companyDepartment}", [CompanyDepartmentController::class, "show"]);

            Route::put("/company_department/{companyDepartment}", [CompanyDepartmentController::class, "update"]);

            Route::delete("/company_department/{companyDepartment}", [CompanyDepartmentController::class, "destroy"]);
        });
        Route::group(['middleware' => ["ability:filter-company_department"]], function () {
            Route::get("/company_department/department/{department}", [CompanyDepartmentController::class, "showByDepartment"]);

            Route::get("/company_department/company/{company}", [CompanyDepartmentController::class, "showByCompany"]);
        });
        Route::post("/users", [UserController::class, "store"])->middleware(
            'ability:create-department-head,create-department-employee,create-company-representative,create-student'
        );

    });

});
