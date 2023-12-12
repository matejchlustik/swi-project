<?php

use App\Models\Program;
use App\Models\Department;
use App\Http\Controllers\EvaluationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PracticeOffersController;
use App\Http\Controllers\PracticeRecordsController;
use App\Http\Controllers\CompanyDepartmentController;
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

            Route::group(['middleware' => ['ability:manage-comments']], function () {
                Route::put('/comments/{comment}', [CommentController::class, 'update']);
                Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
                Route::post('/practices/{practice}/comments', [CommentController::class, 'store']);
            });

            Route::group(['middleware' => ["ability:read-practices"]], function () {
                Route::get('/practices/{practice}/evaluations', [EvaluationController::class, 'index']);
                Route::get('/practices/{practice}/comments', [CommentController::class, 'index']);
                Route::get('practices/{practice}/practice_records', [PracticeRecordsController::class, "index"]);
                Route::get('/practices/{practice}/feedback', [FeedbackController::class, 'index']);
                Route::get('/practices', [PracticeController::class, "index"]);
                Route::get('/practices/{practice}', [PracticeController::class, "show"]);
            });

            Route::group(['middleware' => ["ability:manage-practices"]], function () {
                Route::post('/practices/{practice}/practice_records', [PracticeRecordsController::class, "store"]);
                Route::put('/practice_records/{practice_record}', [PracticeRecordsController::class, "update"]);
                Route::delete('/practice_records/{practice_record}', [PracticeRecordsController::class, "destroy"]);
                Route::post('/practices', [PracticeController::class, "store"]);
                Route::post('/practices/{practice}', [PracticeController::class, "update"]);
                Route::delete('/practices/{practice}', [PracticeController::class, "destroy"]);
                Route::get('/practices/{practice}/contract', [PracticeController::class, "download_contract"]);
                Route::get('/practices/{practice}/completion_confirmation', [PracticeController::class, "generateCompletionConfirmation"]);
                Route::get('/practices/{practice}/completion_confirmation/regenerate', [PracticeController::class, "regenerateCompletionConfirmation"]);
            });

            Route::group(['middleware' => ["ability:filter-practices"]], function () {
                Route::get('/practices/programs/{program}', [PracticeController::class, "getPracticesByProgram"]);
                Route::get('/practices/practice-statuses/{status}', [PracticeController::class, "getPracticesByPracticeStatus"]);
            });

            Route::put("/companies/{company}", [CompanyController::class, "update"])->middleware('ability:edit-company');
            Route::get("/companies", [CompanyController::class, "index"]);
            Route::get("/companies/{company}", [CompanyController::class, "show"]);

            Route::group(['middleware' => ["ability:manage-company"]], function () {
                Route::delete("/companies/{company}", [CompanyController::class, "destroy"]);
                Route::post("/companies", [CompanyController::class, "store"]);
            });

            Route::group(['middleware' => ["ability:manage-feedback"]], function () {
                Route::post("/practices/{practice}/feedback/", [FeedbackController::class, "store"]);
                Route::delete('/feedback/{feedback}', [FeedbackController::class, "destroy"]);
                Route::put('/feedback/{feedback}', [FeedbackController::class, 'update']);
            });

            Route::group(['middleware' => ["ability:manage-workplaces"]], function () {
                Route::post("/faculties", [FacultyController::class, "store"]);
                Route::delete('/faculties/{faculty}', [FacultyController::class, "destroy"]);  
                Route::put('/faculties/{faculty}', [FacultyController::class, 'update']);
                Route::post("/departments", [DepartmentController::class, "store"]);
                Route::delete('/departments/{department}', [DepartmentController::class, "destroy"]);  
                Route::put('/departments/{department}', [DepartmentController::class, 'update']);
                Route::post("/majors", [MajorController::class, "store"]);
                Route::delete('/majors/{major}', [MajorController::class, "destroy"]);  
                Route::put('/majors/{major}', [MajorController::class, 'update']);
                Route::post("/programs", [ProgramController::class, "store"]);
                Route::delete('/programs/{program}', [ProgramController::class, "destroy"]);  
                Route::put('/programs/{program}', [ProgramController::class, 'update']);
            });
            
            Route::group(['middleware' => ["ability:read-workplaces"]], function () {
                Route::get('/faculties', [FacultyController::class, 'index']);
                Route::get('/faculties/{faculty}', [FacultyController::class, "show"]);
                Route::get('/departments', [DepartmentController::class, 'index']);
                Route::get('/departments/{department}', [DepartmentController::class, "show"]);
                Route::get('/majors', [MajorController::class, 'index']);
                Route::get('/majors/{major}', [MajorController::class, "show"]);
                Route::get('/programs', [ProgramController::class, 'index']);
                Route::get('/programs/{program}', [ProgramController::class, "show"]);
            });

            Route::group(['middleware' => ["ability:manage-evaluation"]], function () {
                Route::post('/practices/{practice}/evaluations', [EvaluationController::class, 'store']);
                Route::put('/evaluations/{evaluation}', [EvaluationController::class, 'update']);
                Route::delete('/evaluations/{evaluation}', [EvaluationController::class, 'destroy']);
            });

    });



});

