<<<<<<< HEAD
<?php

require_once __DIR__ . '/../vendor/autoload.php';
\Core\DotEnv::load(__DIR__ . '/..');

(new \App\Middleware\CorsMiddleware())->handle();

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH) ?: '/';

if ($path === '/' || $path === '/index.html') {
    include __DIR__ . '/index.html';
    exit;
}

if (env('APP_ENV') === 'local') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

require_once __DIR__ . '/../routes/api.php';
=======
<?php

require_once __DIR__ . '/../vendor/autoload.php';
\Core\DotEnv::load(__DIR__ . '/..');

(new \App\Middleware\CorsMiddleware())->handle();

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH) ?: '/';

if ($path === '/' || $path === '/index.html') {
    include __DIR__ . '/index.html';
    exit;
}

if (env('APP_ENV') === 'local') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

require_once __DIR__ . '/../routes/api.php';
>>>>>>> 1677249db46651c02f284a34ba822aec3bee5818
\Core\Router::dispatch();