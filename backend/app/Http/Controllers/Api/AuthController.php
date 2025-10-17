<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kullanici;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Kullanıcı girişi
     */
    public function login(Request $request)
    {
        try {
            Log::info('Login attempt started', [
                'email' => $request->email,
                'has_password' => !empty($request->password)
            ]);

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                Log::error('Login validation failed', ['errors' => $validator->errors()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 400);
            }

            Log::info('Attempting authentication', ['email' => $request->email]);

            // Kullanıcıyı manuel kontrol et
            $user = Kullanici::where('email', $request->email)->first();
            if ($user) {
                Log::info('User found', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'password_check' => Hash::check($request->password, $user->sifre)
                ]);
            } else {
                Log::error('User not found', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Email veya şifre hatalı'
                ], 401);
            }

            // Manuel authentication ve token oluşturma
            if (!Hash::check($request->password, $user->sifre)) {
                Log::error('Manual authentication failed');
                return response()->json([
                    'success' => false,
                    'message' => 'Email veya şifre hatalı'
                ], 401);
            }

            // JWT token oluştur
            $token = JWTAuth::fromUser($user);
            
            Log::info('Manual authentication successful', ['token_generated' => !empty($token)]);

            return response()->json([
                'success' => true,
                'message' => 'Giriş başarılı',
                'data' => [
                    'kullanici' => [
                        'id' => $user->id,
                        'ad_soyad' => $user->ad_soyad,
                        'email' => $user->email,
                        'rol' => $user->rol,
                    ],
                    'token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => JWTAuth::factory()->getTTL() * 60
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Login Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Internal server error: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Kullanıcı kaydı
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ad_soyad' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:kullanicilar',
            'sifre' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:admin,pazarlama_uzmani,direktor,bolge_mimari'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 400);
        }

        $kullanici = Kullanici::create([
            'ad_soyad' => $request->ad_soyad,
            'email' => $request->email,
            'sifre' => $request->sifre,
            'rol' => $request->rol,
        ]);

        $token = JWTAuth::fromUser($kullanici);

        return response()->json([
            'success' => true,
            'message' => 'Kullanıcı başarıyla oluşturuldu',
            'data' => [
                'kullanici' => [
                    'id' => $kullanici->id,
                    'ad_soyad' => $kullanici->ad_soyad,
                    'email' => $kullanici->email,
                    'rol' => $kullanici->rol,
                ],
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60
            ]
        ], 201);
    }

    /**
     * Kullanıcı çıkışı
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'Çıkış başarılı'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Çıkış yapılamadı'
            ], 500);
        }
    }

    /**
     * Token yenileme
     */
    public function refresh()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'data' => [
                    'token' => $newToken,
                    'token_type' => 'bearer',
                    'expires_in' => JWTAuth::factory()->getTTL() * 60
                ]
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token yenilenemedi'
            ], 500);
        }
    }

    /**
     * Kullanıcı profili
     */
    public function profile()
    {
        try {
            $kullanici = JWTAuth::parseToken()->authenticate();
            return response()->json([
                'success' => true,
                'data' => [
                    'kullanici' => [
                        'id' => $kullanici->id,
                        'ad_soyad' => $kullanici->ad_soyad,
                        'email' => $kullanici->email,
                        'rol' => $kullanici->rol,
                        'created_at' => $kullanici->created_at,
                        'updated_at' => $kullanici->updated_at,
                    ]
                ]
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kullanıcı bulunamadı'
            ], 404);
        }
    }
}
