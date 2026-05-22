<?php

// ──────────────────────────────────────────
// 1. Autoload — أول حاجة دايماً
// ──────────────────────────────────────────
require_once __DIR__ . '/../vendor/autoload.php';

// ──────────────────────────────────────────
// 2. Environment — قبل أي استخدام لـ env()
// ──────────────────────────────────────────
\Core\DotEnv::load(__DIR__ . '/..');

// ──────────────────────────────────────────
// 3. Error display — قبل أي output
// ──────────────────────────────────────────
if (env('APP_ENV') === 'local') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// ──────────────────────────────────────────
// 4. Global Error Handler
// ──────────────────────────────────────────
\Core\ErrorHandler::register();

// ──────────────────────────────────────────
// 5. CORS
// ──────────────────────────────────────────
(new \App\Middleware\CorsMiddleware())->handle();

// ──────────────────────────────────────────
// 6. Routing
// ──────────────────────────────────────────
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// Landing page
if ($path === '/' || $path === '/index.html') {
    include __DIR__ . '/index.html';
    exit;
}

// Service Worker — يتعامل معاه كـ JS مش كـ PHP
if ($path === '/sw.js') {
    header('Content-Type: application/javascript; charset=utf-8');
    readfile(__DIR__ . '/sw.js');
    exit;
}

// API
require_once __DIR__ . '/../routes/api.php';
\Core\Router::dispatch();