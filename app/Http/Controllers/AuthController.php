<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        return response()->json([
            'data' => $user,
        ]);
    }

    public function login(Request $request)
    {
        try {
            // 1. Validasi Input (Status 422 - Unprocessable Entity)
            // Memastikan email dan password tidak kosong saat dikirim
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal login. Pastikan format input benar.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // 2. Cek apakah Email ada di database (Status 404 - Not Found)
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak terdaftar di sistem kami.'
                ], 404);
            }

            // 3. Cek apakah Password cocok (Status 401 - Unauthorized)
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password yang Anda masukkan salah.'
                ], 401);
            }

            // 4. Jika semua benar, buat Token (Status 200 - OK)
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 200);

        } catch (Exception $e) {
            // 5. Tangkap error sistem atau database (Status 500 - Internal Server Error)
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server kami.',
                'error' => $e->getMessage() // Hapus baris ini saat aplikasi sudah rilis ke publik (Production)
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}