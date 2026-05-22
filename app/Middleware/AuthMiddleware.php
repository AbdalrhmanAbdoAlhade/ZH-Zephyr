<?php

namespace App\Middleware;

use Core\Middleware;
use Core\Response;

class AuthMiddleware implements Middleware
{
    public function handle(): bool
    {
        $headers    = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        $secretToken = env('APP_SECRET');

        if (!$secretToken) {
            Response::json([
                "status"  => false,
                "message" => "Server misconfiguration: APP_SECRET is not set."
            ], 500);
            return false;
        }

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            if (hash_equals($secretToken, $matches[1])) {
                return true;
            }
        }

        Response::json([
            "status"  => false,
            "message" => "Unauthorized access. Invalid or missing Bearer Token."
        ], 401);

        return false;
    }
}