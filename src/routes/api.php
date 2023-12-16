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

            Route::group(['middleware' => ['ability:manage-comments']], function () {
                Route::put('/comments/{comment}', [CommentController::class, 'update']);
                Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
                Route::post('/practices/{practice}/comments', [CommentController::class, 'store']);
                Route::get('/practices/{practice}/comments', [CommentController::class, 'index']);
            });

            Route::group(['middleware' => ["ability:read-practices"]], function () {
                Route::get('practices/{practice}/practice_records', [PracticeRecordsController::class, "index"]);
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
                Route::get('/practices/practice-statuses/{practice_status}', [PracticeController::class, "getPracticesByPracticeStatus"]);
            });

            Route::put("/companies/{company}", [CompanyController::class, "update"])->middleware('ability:edit-company,manage-company');
            Route::get("/companies", [CompanyController::class, "index"]);
            Route::get("/companies/{company}", [CompanyController::class, "show"]);

            Route::group(['middleware' => ["ability:manage-company"]], function () {
                Route::delete("/companies/{company}", [CompanyController::class, "destroy"]);
                Route::post("/companies", [CompanyController::class, "store"]);
            });

            Route::post("/practices/{practice}/feedback/", [FeedbackController::class, "store"]);
            Route::delete('/feedback/{feedback}', [FeedbackController::class, "destroy"]);
            Route::put('/feedback/{feedback}', [FeedbackController::class, 'update']);
            Route::get('/practices/{practice}/feedback', [FeedbackController::class, 'index']);

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

            Route::get('/practices/{practice}/evaluations', [EvaluationController::class, 'index'])->middleware('ability:read-evaluation,manage-evaluation');

            Route::group(['middleware' => ["ability:manage-users"]], function () {
                Route::get("/users", [UserController::class, "index"]);
                Route::get("/users/role/{role}", [UserController::class, "showByRole"]);
                Route::get("/users/{user}", [UserController::class, "show"])->withTrashed();
                Route::delete('/users/{user}', [UserController::class, "destroy"]);
                Route::get("/users/departments/{department}", [UserController::class, "showByDepartment"])->withTrashed();
            });

            Route::post("/users", [UserController::class, "store"])->middleware(
                'ability:create-department-head,create-department-employee,create-company-representative,create-student'
            );

            Route::put('/users/{user}', [UserController::class, "update"]);

            Route::group(['middleware' => ["ability:admin-deleted-data"]], function () {
                Route::post("/departments/{department}/restore", [DepartmentController::class, "restore"])->withTrashed();
                Route::post("/comment/{comment}/restore", [CommentController::class, "restore"])->withTrashed();
                Route::post("/company/{company}/restore", [CommentController::class, "restore"])->withTrashed();
                Route::post("/company_department/{company_department}/restore", [CompanyDepartmentController::class, "restore"])->withTrashed();
                Route::post("/faculties/{faculty}/restore", [FacultyController::class, "restore"])->withTrashed();
                Route::post("/feedback/{feedback}/restore", [FeedbackController::class, "restore"])->withTrashed();
                Route::post("/majors/{major}/restore", [MajorController::class, "restore"])->withTrashed();
                Route::post("/practices/{practice}/restore", [PracticeController::class, "restore"])->withTrashed();
                Route::post("/practice_offers/{practice_offer}/restore", [PracticeOffersController::class, "restore"])->withTrashed();
                Route::post("/practice_records/{practice_record}/restore", [PracticeRecordsController::class, "restore"])->withTrashed();
                Route::post("/programs/{program}/restore", [ProgramController::class, "restore"])->withTrashed();
                Route::post("/users/{user}/restore", [UserController::class, "restore"])->withTrashed();
                Route::delete("/departments/{department}/force-delete", [DepartmentController::class, "forceDelete"])->withTrashed();
                Route::delete("/comment/{comment}/force-delete", [CommentController::class, "forceDelete"])->withTrashed();
                Route::delete("/company/{company}/force-delete", [CommentController::class, "forceDelete"])->withTrashed();
                Route::delete("/company_department/{company_department}/force-delete", [CompanyDepartmentController::class, "forceDelete"])->withTrashed();
                Route::delete("/faculties/{faculty}/force-delete", [FacultyController::class, "forceDelete"])->withTrashed();
                Route::delete("/feedback/{feedback}/force-delete", [FeedbackController::class, "forceDelete"])->withTrashed();
                Route::delete("/majors/{major}/force-delete", [MajorController::class, "forceDelete"])->withTrashed();
                Route::delete("/practices/{practice}/force-delete", [PracticeController::class, "forceDelete"])->withTrashed();
                Route::delete("/practice_offers/{practice_offer}/force-delete", [PracticeOffersController::class, "forceDelete"])->withTrashed();
                Route::delete("/practice_records/{practice_record}/force-delete", [PracticeRecordsController::class, "forceDelete"])->withTrashed();
                Route::delete("/programs/{program}/force-delete", [ProgramController::class, "forceDelete"])->withTrashed();
                Route::delete("/users/{user}/force-delete", [UserController::class, "forceDelete"])->withTrashed();
                Route::get("/departments/soft_deleted", [DepartmentController::class, "indexDeleted"])->withTrashed();
                Route::get("/comment/soft_deleted", [CommentController::class, "indexDeleted"])->withTrashed();
                Route::get("/company/soft_deleted", [CommentController::class, "indexDeleted"])->withTrashed();
                Route::get("/company_department/soft_deleted", [CompanyDepartmentController::class, "indexDeleted"])->withTrashed();
                Route::get("/faculties/soft_deleted", [FacultyController::class, "indexDeleted"])->withTrashed();
                Route::get("/feedback/soft_deleted", [FeedbackController::class, "indexDeleted"])->withTrashed();
                Route::get("/majors/soft_deleted", [MajorController::class, "indexDeleted"])->withTrashed();
                Route::get("/practices/soft_deleted", [PracticeController::class, "indexDeleted"])->withTrashed();
                Route::get("/practice_offers/soft_deleted", [PracticeOffersController::class, "indexDeleted"])->withTrashed();
                Route::get("/practice_records/soft_deleted", [PracticeRecordsController::class, "indexDeleted"])->withTrashed();
                Route::get("/programs/soft_deleted", [ProgramController::class, "indexDeleted"])->withTrashed();
                Route::get("/users/soft_deleted", [UserController::class, "indexDeleted"])->withTrashed();
            });

    });



});

