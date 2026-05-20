<?php

namespace Core;

class Request 
{
    // الحصول على الـ URI الحالي (مثلاً: /api/users)
    public static function getUri(): string 
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        // تنظيف الـ URI من أي Query Parameters (مثل: ?id=1)
        return parse_url($uri, PHP_URL_PATH) ?: '/';
    }

    // الحصول على الـ HTTP Method (GET, POST, etc.)
    public static function getMethod(): string 
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }
    
    public function body(): array 
{
    return self::getBody();
}

    // جلب وتصفية كل البيانات القادمة (سواء من الـ URL أو الـ Body)
    public static function getBody(): array 
    {
        $data = [];

        // إذا كان الطلب GET
        if (self::getMethod() === 'GET') {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        // إذا كان الطلب POST أو يحتوي على Payload (مثل JSON)
        if (self::getMethod() === 'POST') {
            // لدعم الـ JSON APIs
            $input = json_decode(file_get_contents('php://input'), true);
            if (is_array($input)) {
                foreach ($input as $key => $value) {
                    $data[$key] = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            } else {
                foreach ($_POST as $key => $value) {
                    $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }

        return $data;
    }
}