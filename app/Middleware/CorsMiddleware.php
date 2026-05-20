<?php

namespace App\Middleware;

use Core\Middleware;

class CorsMiddleware implements Middleware 
{
    public function handle(): bool 
    {
        // السماح لأي دومين بالوصول للـ API (تقدر تخصصه في الـ .env لاحقاً)
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        // إذا كان الطلب من نوع OPTIONS (Preflight Request) نقفله هنا بنجاح
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        return true;
    }
}