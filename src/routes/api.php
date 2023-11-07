<?php

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
Route::put("/companies/{company}", [UserController::class, "update"]);
Route::delete("/companies/{company}", [UserController::class, "delete"]);
Route::get("/companies", [CompanyController::class, "index"]);
Route::get("/companies/{company}", [CompanyController::class, "show"]);
Route::post("/companies", [CompanyController::class, "store"]);
Route::middleware('auth:sanctum')->get('/testing', function (Request $request) {
    return "hello";
});
