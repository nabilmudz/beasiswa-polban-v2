<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ValidateJwt
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Step 1: Ambil token dari header
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'status' => 401,
                'errorCode' => 'UNAUTHORIZED',
                'message' => 'Token tidak ditemukan',
                'timestamp' => now()->toIso8601String(),
            ], 401);
        }

        $token = substr($authHeader, 7);

        try {
            // Step 2: Ambil JWKS dari SSO (di-cache 1 jam)
            $jwks = Cache::remember('sso_jwks', 3600, function () {
                $response = Http::get(config('services.sso.jwks_url'));
                return $response->json();
            });

            // Verifikasi signature & decode
            $decoded = JWT::decode($token, JWK::parseKeySet($jwks));
            $payload = (array) $decoded;

            // Step 3: Cek exp sudah ditangani otomatis oleh JWT::decode

            // Step 4: Cek role jika ada restriction
            if (!empty($roles) && !in_array($payload['role'], $roles)) {
                return response()->json([
                    'status' => 403,
                    'errorCode' => 'FORBIDDEN',
                    'message' => 'Role ' . $payload['role'] . ' tidak diizinkan mengakses endpoint ini',
                    'timestamp' => now()->toIso8601String(),
                ], 403);
            }

            // Step 5: Simpan user info ke request
            $request->merge([
                'jwt_user_id'  => $payload['sub'],
                'jwt_nim'      => $payload['nim'] ?? null,
                'jwt_npa'      => $payload['npa'] ?? null,
                'jwt_email'    => $payload['email'],
                'jwt_role'     => $payload['role'],
                'jwt_jti'      => $payload['jti'],
            ]);

        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json([
                'status' => 401,
                'errorCode' => 'UNAUTHORIZED',
                'message' => 'Token sudah kedaluwarsa',
                'timestamp' => now()->toIso8601String(),
            ], 401);
        } catch (\Throwable $e) {
            // Jika JWKS tidak bisa diakses
            if (str_contains($e->getMessage(), 'cURL') || str_contains($e->getMessage(), 'Connection')) {
                return response()->json([
                    'status' => 503,
                    'errorCode' => 'SERVICE_UNAVAILABLE',
                    'message' => 'SSO tidak dapat dijangkau',
                    'timestamp' => now()->toIso8601String(),
                ], 503);
            }
            return response()->json([
                'status' => 401,
                'errorCode' => 'UNAUTHORIZED',
                'message' => 'Token tidak valid',
                'timestamp' => now()->toIso8601String(),
            ], 401);
        }

        return $next($request);
    }
}