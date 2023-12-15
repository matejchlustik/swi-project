<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\PracticeRecordsController;
use App\Http\Controllers\CompanyDepartmentController;
use App\Http\Controllers\PracticeOffersController;

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

            Route::group(['middleware' => ["ability:manage-practice-offers"]], function () {

                Route::post("/practice_offers", [PracticeOffersController::class, "store"]);

                Route::put("/practice_offers/{practiceOffer}", [PracticeOffersController::class, "update"]);

                Route::delete("/practice_offers/{practiceOffer}", [PracticeOffersController::class, "destroy"]);
            });

            Route::get("/practice_offers", [PracticeOffersController::class, "index"]);

            Route::get("/practice_offers/{practiceOffer}", [PracticeOffersController::class, "show"]);

            Route::get("/practice_offers/departments/{department}", [PracticeOffersController::class, "showByDepartment"]);

            Route::get("/practice_offers/companies/{company}", [PracticeOffersController::class, "showByCompany"]);

            Route::group(['middleware' => ["ability:manage-company-department"]], function () {

                Route::get("/company_department", [CompanyDepartmentController::class, "index"]);

                Route::post("/company_department", [CompanyDepartmentController::class, "store"]);

                Route::get("/company_department/{companyDepartment}", [CompanyDepartmentController::class, "show"]);

                Route::put("/company_department/{companyDepartment}", [CompanyDepartmentController::class, "update"]);

                Route::delete("/company_department/{companyDepartment}", [CompanyDepartmentController::class, "destroy"]);

                Route::get("/company_department/departments/{department}", [CompanyDepartmentController::class, "showByDepartment"]);

                Route::get("/company_department/companies/{company}", [CompanyDepartmentController::class, "showByCompany"]);
            });

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
            });
        Route::group(['middleware' => ["ability:edit-company"]], function () {

            Route::put("/companies/{company}", [CompanyController::class, "update"]);

        });
        Route::group(['middleware' => ["ability:read-company"]], function () {

            Route::get("/companies", [CompanyController::class, "index"]);

            Route::get("/companies/{company}", [CompanyController::class, "show"]);

        });
    });
    Route::group(['middleware' => ["ability:manage-company"]], function () {

        Route::delete("/companies/{company}", [CompanyController::class, "destroy"]);

        Route::post("/companies", [CompanyController::class, "store"]);
    });
    Route::group(['middleware' => ["ability:manage-users"]], function () {

        Route::get("/users/soft_deleted", [UserController::class, "indexDeleted"]);

        Route::delete('/users/{user}/force-delete', [UserController::class, "destroy"]);

        Route::post('/users', [UserController::class, "store"]);

        Route::post('/users/{user}/restore', [UserController::class, "restore"])->withTrashed();
    });
    Route::group(['middleware' => ["ability:manage-users-other"]], function () {
        Route::get("/users", [UserController::class, "index"]);

        Route::get("/users/role", [UserController::class, "showByRole"]);

        Route::get("/users/{user}", [UserController::class, "show"])->withTrashed();

        Route::delete('/users/{user}', [UserController::class, "deactivate"]);

        Route::get("/users/departments/{department}", [UserController::class, "showByDepartment"]);

    });

    Route::put('/users/{user}', [UserController::class, "update"]);

    Route::group(['middleware' => ["ability:manage-wo-admin-wo-dephead"]], function () {

    });
});
