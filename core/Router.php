<<<<<<< HEAD
<?php

namespace Core;

class Router
{
    protected static array $routes = [
        'GET'    => [],
        'POST'   => [],
        'PUT'    => [],
        'PATCH'  => [],
        'DELETE' => [],
    ];

    protected static array $middlewareMap = [];
    protected static string $lastRoute = '';

    // ──────────────────────────────────────────
    // Route Registration
    // ──────────────────────────────────────────

    public static function get(string $path, $callback): self
    {
        return self::addRoute('GET', $path, $callback);
    }

    public static function post(string $path, $callback): self
    {
        return self::addRoute('POST', $path, $callback);
    }

    public static function put(string $path, $callback): self
    {
        return self::addRoute('PUT', $path, $callback);
    }

    public static function patch(string $path, $callback): self
    {
        return self::addRoute('PATCH', $path, $callback);
    }

    public static function delete(string $path, $callback): self
    {
        return self::addRoute('DELETE', $path, $callback);
    }

    private static function addRoute(string $method, string $path, $callback): self
    {
        self::$routes[$method][$path] = $callback;
        self::$lastRoute = $method . ':' . $path;
        return new self();
    }

    // ──────────────────────────────────────────
    // Middleware
    // ──────────────────────────────────────────

    public function middleware(string $middlewareClass): self
    {
        self::$middlewareMap[self::$lastRoute][] = $middlewareClass;
        return $this;
    }

    // ──────────────────────────────────────────
    // Dispatch
    // ──────────────────────────────────────────

    public static function dispatch(): void
    {
        $path   = Request::getUri();
        $method = Request::getMethod();

        // Support method spoofing via _method field (for HTML forms / some clients)
        if ($method === 'POST') {
            $spoofed = $_POST['_method'] ?? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] ?? null;
            if ($spoofed && in_array(strtoupper($spoofed), ['PUT', 'PATCH', 'DELETE'])) {
                $method = strtoupper($spoofed);
            }
        }

        if (!isset(self::$routes[$method])) {
            Response::json([
                "status"  => false,
                "message" => "Method [{$method}] not allowed."
            ], 405);
            return;
        }

        // 1. Try exact match first
        $routeKey = $method . ':' . $path;
        if (isset(self::$routes[$method][$path])) {
            self::runRoute($method, $path, $routeKey, $path, []);
            return;
        }

        // 2. Try dynamic match e.g. /users/{id}
        foreach (self::$routes[$method] as $routePath => $callback) {
            $params = self::matchDynamic($routePath, $path);
            if ($params !== null) {
                $routeKey = $method . ':' . $routePath;
                self::runRoute($method, $routePath, $routeKey, $path, $params);
                return;
            }
        }

        // 3. Not found
        Response::json([
            "status"  => false,
            "message" => "Route [{$path}] not found on this server."
        ], 404);
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /**
     * Match a dynamic route like /users/{id}/posts/{postId}
     * Returns associative array of params if matched, null otherwise.
     */
    private static function matchDynamic(string $routePath, string $requestPath): ?array
    {
        // Convert /users/{id} → regex: /users/([^/]+)
        $pattern = preg_replace('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', '([^/]+)', $routePath);
        $pattern = '@^' . $pattern . '$@';

        if (!preg_match($pattern, $requestPath, $matches)) {
            return null;
        }

        // Extract param names from route definition
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $routePath, $paramNames);

        array_shift($matches); // remove full match

        return array_combine($paramNames[1], $matches);
    }

    /**
     * Run middleware stack + controller for a matched route.
     */
    private static function runRoute(
        string $method,
        string $routePath,
        string $routeKey,
        string $requestPath,
        array  $params
    ): void {
        // Run middleware
        if (isset(self::$middlewareMap[$routeKey])) {
            foreach (self::$middlewareMap[$routeKey] as $middlewareClass) {
                if (class_exists($middlewareClass)) {
                    $middlewareInstance = new $middlewareClass();
                    $middlewareInstance->handle();
                }
            }
        }

        $callback        = self::$routes[$method][$routePath];
        $requestInstance = new \Core\Request();

        // Inject dynamic params into Request
        foreach ($params as $key => $value) {
            $requestInstance->setParam($key, $value);
        }

        // Closure
        if (is_callable($callback)) {
            echo call_user_func($callback, $requestInstance);
            return;
        }

        // [Controller::class, 'method']
        if (is_array($callback)) {
            [$controllerName, $methodName] = $callback;

            if (class_exists($controllerName)) {
                $controllerInstance = new $controllerName();

                if (method_exists($controllerInstance, $methodName)) {
                    $controllerInstance->$methodName($requestInstance);
                    return;
                }

                Response::json([
                    "status"  => false,
                    "message" => "Method [{$methodName}] not found in [{$controllerName}]."
                ], 500);
                return;
            }

            Response::json([
                "status"  => false,
                "message" => "Controller [{$controllerName}] not found."
            ], 500);
        }
    }
=======
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
>>>>>>> 1677249db46651c02f284a34ba822aec3bee5818
}