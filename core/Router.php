<?php

namespace Core;

class Router 
{
    protected static array $routes = ['GET' => [], 'POST' => []];
    protected static array $middlewareMap = [];
    protected static string $lastRoute = '';

    public static function get(string $path, $callback): self 
    {
        self::$routes['GET'][$path] = $callback;
        self::$lastRoute = 'GET:' . $path;
        return new self(); 
    }

    public static function post(string $path, $callback): self 
    {
        self::$routes['POST'][$path] = $callback;
        self::$lastRoute = 'POST:' . $path;
        return new self();
    }

    public function middleware(string $middlewareClass): self 
    {
        self::$middlewareMap[self::$lastRoute][] = $middlewareClass;
        return $this;
    }

    public static function dispatch(): void 
    {
        $path   = Request::getUri();
        $method = Request::getMethod();
        $routeKey = $method . ':' . $path;

        if (isset(self::$routes[$method][$path])) {
            
            if (isset(self::$middlewareMap[$routeKey])) {
                foreach (self::$middlewareMap[$routeKey] as $middlewareClass) {
                    if (class_exists($middlewareClass)) {
                        $middlewareInstance = new $middlewareClass();
                        $middlewareInstance->handle();
                    }
                }
            }

            $callback = self::$routes[$method][$path];

            if (is_callable($callback)) {
                echo call_user_func($callback);
                return;
            }

            if (is_array($callback)) {
            $controllerName = $callback[0];
            $methodName     = $callback[1];

            if (class_exists($controllerName)) {
                $controllerInstance = new $controllerName();
                
                if (method_exists($controllerInstance, $methodName)) {
                    // تحويل الـ Request لكائن يمرر تلقائياً للدالة
                    $requestInstance = new \Core\Request();
                    $controllerInstance->$methodName($requestInstance);
                    return;
                }
            }
        }
        }

        Response::json([
            "status"  => false,
            "message" => "Route [{$path}] not found on this server."
        ], 404);
    }
}