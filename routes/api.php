<?php

// routes/api.php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PejabatController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// SEMUA ROUTE DI DALAM GRUP INI AKAN OTOMATIS MENGECEK TOKEN
Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('pejabat', PejabatController::class);
    Route::post('/logout', [AuthController::class, 'logout']);

});