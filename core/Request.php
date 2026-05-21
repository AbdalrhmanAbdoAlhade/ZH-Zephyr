<?php

namespace Core;

class Request 
{
    protected static array $routeParams = [];

    public static function getUri(): string 
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return parse_url($uri, PHP_URL_PATH) ?: '/';
    }

    public static function getMethod(): string 
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        
        if ($method === 'POST') {
            if (isset($_POST['_method'])) {
                return strtoupper($_POST['_method']);
            }
            if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
                return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
            }
            
            $input = json_decode(file_get_contents('php://input'), true);
            if (is_array($input) && isset($input['_method'])) {
                return strtoupper($input['_method']);
            }
        }
        
        return $method;
    }
    
    public function body(): array 
    {
        return self::getBody();
    }

    public static function getBody(): array 
    {
        $data = [];
        $method = self::getMethod();

        foreach ($_GET as $key => $value) {
            $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $input = json_decode(file_get_contents('php://input'), true);
            if (is_array($input)) {
                foreach ($input as $key => $value) {
                    $data[$key] = is_string($value) ? filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS) : $value;
                }
            } else {
                if ($method === 'POST' || (isset($_SERVER['CONTENT_TYPE']) && str_contains($_SERVER['CONTENT_TYPE'], 'application/x-www-form-urlencoded'))) {
                    $source = ($method === 'POST') ? $_POST : [];
                    if (empty($source)) {
                        parse_str(file_get_contents('php://input'), $source);
                    }
                    foreach ($source as $key => $value) {
                        $data[$key] = is_string($value) ? filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS) : $value;
                    }
                }
            }
        }

        return $data;
    }

    public static function setParam(string $key, $value): void
    {
        self::$routeParams[$key] = $value;
    }

    public function param(string $key, $default = null)
    {
        return self::$routeParams[$key] ?? $default;
    }

    public function params(): array
    {
        return self::$routeParams;
    }
}
