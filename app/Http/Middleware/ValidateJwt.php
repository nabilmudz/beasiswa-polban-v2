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
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->error(401, 'UNAUTHORIZED', 'Token tidak ditemukan');
        }

        $token = substr($authHeader, 7);

        try {
            $jwksUrl = config('services.sso.jwks_url');
            $jwks = Cache::remember('sso_jwks', 3600, function () use ($jwksUrl) {
                $response = Http::timeout(5)->get($jwksUrl);
                if (!$response->ok()) {
                    throw new \Exception('JWKS fetch failed');
                }
                return $response->json();
            });

            // Verifikasi signature & decode token
            $decoded = JWT::decode($token, JWK::parseKeySet($jwks));
            $payload = (array) $decoded;

            if (!empty($roles) && !in_array($payload['role'], $roles)) {
                return $this->error(403, 'FORBIDDEN', 
                    'Role ' . ($payload['role'] ?? '?') . ' tidak diizinkan mengakses endpoint ini'
                );
            }
            
            $request->merge([
                'jwt_user_id' => $payload['sub'],
                'jwt_nim'     => $payload['nim'] ?? null,
                'jwt_npa'     => $payload['npa'] ?? null,
                'jwt_email'   => $payload['email'],
                'jwt_role'    => $payload['role'],
                'jwt_jti'     => $payload['jti'],
            ]);

        } catch (\Firebase\JWT\ExpiredException $e) {
            return $this->error(401, 'UNAUTHORIZED', 'Token sudah kedaluwarsa');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Cache::forget('sso_jwks'); // Hapus cache rusak jika SSO gagal kontak
            return $this->error(503, 'SERVICE_UNAVAILABLE', 'SSO tidak dapat dijangkau');
        } catch (\Throwable $e) {
            // Antisipasi kegagalan cURL/koneksi lain yang dilempar di dalam scope Cache
            if (str_contains($e->getMessage(), 'cURL') || str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'failed')) {
                Cache::forget('sso_jwks');
                return $this->error(503, 'SERVICE_UNAVAILABLE', 'SSO tidak dapat dijangkau');
            }
            return $this->error(401, 'UNAUTHORIZED', 'Token tidak valid');
        }

        return $next($request);
    }

    /**
     * Helper function untuk standarisasi format Error Response sesuai Base Contract
     */
    private function error(int $status, string $code, string $message)
    {
        return response()->json([
            'status'    => $status,
            'errorCode' => $code,
            'message'   => $message,
            'timestamp' => now()->toIso8601String(),
        ], $status);
    }
}