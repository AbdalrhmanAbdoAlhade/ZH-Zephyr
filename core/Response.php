<?php

namespace Core;

class Response 
{
    public static function json(array $data, int $statusCode = 200): void 
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit; 
    }
}