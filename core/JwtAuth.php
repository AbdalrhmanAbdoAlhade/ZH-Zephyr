<?php

namespace Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Exception;

class JwtAuth
{
    private static string $algo = 'HS256';

    // ──────────────────────────────────────────
    // Generate Token
    // ──────────────────────────────────────────

    public static function generate(array $payload): string
    {
        $secret = env('JWT_SECRET');
        $expiry = (int) env('JWT_EXPIRY', 3600);

        if (!$secret) {
            throw new Exception('JWT_SECRET is not set in .env');
        }

        $claims = array_merge($payload, [
            'iat' => time(),
            'exp' => time() + $expiry,
        ]);

        return JWT::encode($claims, $secret, self::$algo);
    }

    // ──────────────────────────────────────────
    // Verify & Decode Token
    // ──────────────────────────────────────────

    public static function verify(string $token): array|false
    {
        $secret = env('JWT_SECRET');

        if (!$secret) {
            return false;
        }

        try {
            $decoded = JWT::decode($token, new Key($secret, self::$algo));
            return (array) $decoded;
        } catch (ExpiredException) {
            return false;
        } catch (SignatureInvalidException) {
            return false;
        } catch (Exception) {
            return false;
        }
    }
}
