<?php

// 1. استدعاء الـ Autoloader والـ .env
require_once __DIR__ . '/../vendor/autoload.php';
\Core\DotEnv::load(__DIR__ . '/..');

// 2. تشغيل الـ CORS لدعم تطبيقات الـ PWA والموبايل
(new \App\Middleware\CorsMiddleware())->handle();

// 3. إذا طلب المستخدم المسار الرئيسي مباشرة، اعرض صفحة الـ 3D Landing Page
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($uri, PHP_URL_PATH) ?: '/';

if ($path === '/' || $path === '/index.html') {
    include __DIR__ . '/index.html';
    exit;
}

// 4. تفعيل الأخطاء للتطوير بناءً على البيئة الحالية
if (env('APP_ENV') === 'local') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// 5. استدعاء المسارات وتشغيل الـ Router لباقي الـ Endpoints (مثل /api/v1/products)
require_once __DIR__ . '/../routes/api.php';
\Core\Router::dispatch();