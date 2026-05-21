<<<<<<< HEAD
<?php

namespace App\Middleware;

use Core\Middleware;
use Core\Response;

class AuthMiddleware implements Middleware 
{
    public function handle(): bool 
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        $secretToken = "ZH-Innovation-Secure-Token-2026";

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            if ($matches[1] === $secretToken) {
                return true; 
            }
        }

        Response::json([
            "status" => false,
            "message" => "Unauthorized access. Invalid or missing Bearer Token."
        ], 401);

        return false; 
    }
=======
<?php

namespace App\Middleware;

use Core\Middleware;
use Core\Response;

class AuthMiddleware implements Middleware 
{
    public function handle(): bool 
    {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        $secretToken = "ZH-Innovation-Secure-Token-2026";

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            if ($matches[1] === $secretToken) {
                return true; 
            }
        }

        Response::json([
            "status" => false,
            "message" => "Unauthorized access. Invalid or missing Bearer Token."
        ], 401);

        return false; 
    }
>>>>>>> 1677249db46651c02f284a34ba822aec3bee5818
}