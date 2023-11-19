<?php

use App\Http\Controllers\PracticeController;
use App\Http\Controllers\PracticeRecordsController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

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




Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post("/users", [UserController::class, "store"])->middleware(
        'ability:create-department-head,create-department-employee,create-company-representative,create-student'
    );

    Route::post("/logout", [UserController::class, "logout"]);

    Route::get("/test", [CompanyController::class, "index"])->middleware('ability:acces-companies');


    Route::get('/practice_records', [PracticeRecordsController::class, "index"])->middleware('ability:access-records');

    Route::get('/practice_records/{practice_record}', [PracticeRecordsController::class, "show"])->middleware('ability:access-records');

    Route::post('/practice_records', [PracticeRecordsController::class, "store"])->middleware('ability:create-record');

    Route::put('/practice_records/{practice_record}', [PracticeRecordsController::class, "update"])->middleware('ability:update-record');

    Route::delete('/practice_records/{practice_record}', [PracticeRecordsController::class, "destroy"])->middleware('ability:delete-record');


    Route::get('/practices', [PracticeController::class, "index"])->middleware('ability:access-practices');

    Route::get('/practices/{practice}', [PracticeController::class, "show"])->middleware('ability:access-practices');

    Route::post('/practices', [PracticeController::class, "store"])->middleware('ability:create-practice');

    Route::put('/practices/{practice}', [PracticeController::class, "update"])->middleware('ability:update-practice');

    Route::delete('/practices/{practice}', [PracticeController::class, "destroy"])->middleware('ability:delete-practice');

});
