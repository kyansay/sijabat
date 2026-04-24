<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PejabatController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tanpa Autentikasi)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Rute Lupa Password
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Wajib Login / Token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Manajemen Data Pejabat (CRUD)
    Route::apiResource('pejabat', PejabatController::class);
    Route::get('users', [UserController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);

});