<?php

namespace Core;

class Request 
{
    protected static array $routeParams = [];

    // الحصول على الـ URI الحالي (مثلاً: /api/users)
    public static function getUri(): string 
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        // تنظيف الـ URI من أي Query Parameters (مثل: ?id=1)
        return parse_url($uri, PHP_URL_PATH) ?: '/';
    }

    // الحصول على الـ HTTP Method الحقيقية (تدعم الـ Method Spoofing لـ PUT/PATCH/DELETE)
    public static function getMethod(): string 
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        
        if ($method === 'POST') {
            // فحص وجود الـ _method السحرية في الـ Body أو الـ Headers
            if (isset($_POST['_method'])) {
                return strtoupper($_POST['_method']);
            }
            if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
                return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
            }
            
            // فحص الـ JSON Input لو الـ Client بعت _method جوا الـ Payload
            $input = json_decode(file_get_contents('php://input'), true);
            if (is_array($input) && isset($input['_method'])) {
                return strtoupper($input['_method']);
            }
        }
        
        return $method;
    }
    
    // دالة جلب الـ body كـ Instance Method
    public function body(): array 
    {
        return self::getBody();
    }

    // جلب وتصفية كل البيانات القادمة (سواء من الـ URL أو الـ Body لجميع الـ Methods)
    public static function getBody(): array 
    {
        $data = [];
        $method = self::getMethod();

        // 1. جلب بيانات الـ GET (Query String Parameters) دائماً إذا وجدت
        foreach ($_GET as $key => $value) {
            $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        // 2. إذا كانت الـ Method تحمل Payload (POST, PUT, PATCH, DELETE)
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            // دعم الـ JSON APIs لجميع الـ Methods
            $input = json_decode(file_get_contents('php://input'), true);
            if (is_array($input)) {
                foreach ($input as $key => $value) {
                    // حماية وتصفية البيانات إذا كانت نصوص، أو الاحتفاظ بها كما هي لو كانت Array/Object
                    $data[$key] = is_string($value) ? filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS) : $value;
                }
            } else {
                // لدعم الطلبات العادية Form Data (تشتغل تلقائياً مع POST، ونحاكيها مع PUT/DELETE إذا أُرسلت كـ urlencoded)
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

    // تخزين الـ Dynamic Parameters القادمة من الـ Router (يتم استدعاؤها من الـ Router تلقائياً)
    public static function setParams(array $params): void
    {
        self::$routeParams = $params;
    }

    // جلب قيمة باراميتر معين (مثلاً: id من /users/{id})
    public function param(string $key, $default = null)
    {
        return self::$routeParams[$key] ?? $default;
    }

    // جلب كل الـ Dynamic Parameters كـ Array
    public function params(): array
    {
        return self::$routeParams;
    }
}
