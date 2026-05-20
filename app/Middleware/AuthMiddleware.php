<?php

namespace App\Middleware;

use Core\Middleware;
use Core\Response;

class AuthMiddleware implements Middleware 
{
    public function handle(): bool 
    {
        // جلب الـ Headers من الـ Request
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        // هنا بنحط توكن ثابت للتجربة (في المستقبل هتشيك عليه من قاعدة البيانات)
        $secretToken = "ZH-Innovation-Secure-Token-2026";

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            if ($matches[1] === $secretToken) {
                return true; // التوكن صح، اسمح للمشروع يكمل
            }
        }

        // لو التوكن غلط أو مش موجود، ارجع بـ 401 Unauthorized فوراً
        Response::json([
            "status" => false,
            "message" => "Unauthorized access. Invalid or missing Bearer Token."
        ], 401);

        return false; 
    }
}