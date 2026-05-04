<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Contoh method untuk menampilkan profil pengguna
    public function profile()
    {
        // Logika untuk mengambil data pengguna saat ini
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'message' => 'Profil pengguna berhasil diambil.',
            'data' => $user
        ]);
    }

    
}