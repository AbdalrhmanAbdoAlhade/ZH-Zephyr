<?php

namespace App\Middleware;

use Core\Middleware;
use Core\JwtAuth;
use Core\Response;

class AuthMiddleware implements Middleware
{
    public function handle(): bool
    {
        $headers    = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Response::json([
                "status"  => false,
                "message" => "Unauthorized. Bearer token is missing."
            ], 401);
            return false;
        }

        $token   = $matches[1];
        $payload = JwtAuth::verify($token);

        if (!$payload) {
            Response::json([
                "status"  => false,
                "message" => "Unauthorized. Token is invalid or expired."
            ], 401);
            return false;
        }

        // Inject payload into $_REQUEST so controllers can access it
        $_REQUEST['auth'] = $payload;

        return true;
    }
}
