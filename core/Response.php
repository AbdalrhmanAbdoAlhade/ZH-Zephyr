<?php

namespace Core;

class Response 
{
    // دالة موحدة لإرجاع الـ JSON
    public static function json(array $data, int $statusCode = 200): void 
    {
        // مسح أي مخرجات سابقة وتحديد الـ Header كـ JSON
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit; // إنهاء السكريبت فوراً بعد إرسال الـ Response
    }
}