<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'string|in:user,admin|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
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
                        'role' => $user->role
                    ],
                    'token' => $token,
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


    public function forgotPassword(Request $request)
    {
        // Validasi input email
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Buat token acak (64 karakter)
        $token = Str::random(64);

        // Simpan token ke tabel bawaan Laravel (password_reset_tokens)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // Kirim email (gunakan antrean/queue agar cepat)
        Mail::to($request->email)->queue(new ResetPasswordMail($token, $request->email));

        return response()->json([
            'success' => true,
            'message' => 'Link reset password telah dikirim ke email Anda.'
        ]);
    }

    /**
     * 2. Fungsi Memproses Perubahan Password Baru
     */
    public function resetPassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|min:6' // Minimal 6 karakter
        ]);

        // Cek apakah token dan email cocok di database
        $resetRecord = DB::table('password_reset_tokens')->where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();

        if (!$resetRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset password tidak valid atau sudah kedaluwarsa.'
            ], 400);
        }

        // Ganti password user di database
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Hapus token yang sudah terpakai demi keamanan
        DB::table('password_reset_tokens')->where(['email' => $request->email])->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah. Silakan login dengan password baru.'
        ]);
    }
}