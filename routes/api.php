<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PejabatController;
use App\Models\EmailLog;
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

    // Pengiriman Email Peringatan Manual
    Route::post('/pejabat/{id}/send-warning', [PejabatController::class, 'sendManualWarning']);

    // Pengiriman Email dari Dashboard
    Route::post('/kirim-email', [PejabatController::class, 'kirimEmail']);

    // Menampilkan Riwayat Log Penyiaran Email
    Route::get('/email-logs', function () {
        $logs = EmailLog::with('pejabat:id,nama,pangkat_sekarang')
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