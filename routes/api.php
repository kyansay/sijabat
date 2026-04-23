<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PejabatController;
use App\Models\EmailLog;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tanpa Autentikasi)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Wajib Login / Token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Manajemen Data Pejabat (CRUD)
    Route::apiResource('pejabat', PejabatController::class);

    // Pengiriman Email Peringatan Manual
    Route::post('/pejabat/{id}/send-warning', [PejabatController::class, 'sendManualWarning']);

    // Menampilkan Riwayat Log Penyiaran Email
    Route::get('/email-logs', function () {
        $logs = EmailLog::with('pejabat:id,nama,jabatan_sekarang')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat Log Penyiaran Email',
            'data' => $logs
        ]);
    });

    // Proses Logout
    Route::post('/logout', [AuthController::class, 'logout']);

});