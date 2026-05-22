<?php

use Core\Router;
use Core\Response;
use App\Middleware\AuthMiddleware;

// ──────────────────────────────────────────
// Health Check
// ──────────────────────────────────────────
Router::get('/api/health', function () {
    Response::success([
        'version' => '1.0.0',
        'php'     => PHP_VERSION,
    ], 'ZH Zephyr is running');
});

// ──────────────────────────────────────────
// Public Routes
// ──────────────────────────────────────────
Router::post('/api/auth/login', [\App\Controllers\AuthController::class, 'login']);

// ──────────────────────────────────────────
// Protected Routes — require Bearer token
// ──────────────────────────────────────────
Router::get('/api/users', [\App\Controllers\UserController::class, 'index'])
    ->middleware(AuthMiddleware::class);

Router::get('/api/users/{id}', [\App\Controllers\UserController::class, 'show'])
    ->middleware(AuthMiddleware::class);

Router::post('/api/users', [\App\Controllers\UserController::class, 'store'])
    ->middleware(AuthMiddleware::class);

Router::put('/api/users/{id}', [\App\Controllers\UserController::class, 'update'])
    ->middleware(AuthMiddleware::class);

Router::delete('/api/users/{id}', [\App\Controllers\UserController::class, 'destroy'])
    ->middleware(AuthMiddleware::class);

// ──────────────────────────────────────────
// Products Routes
// ──────────────────────────────────────────
Router::get('/api/products', [\App\Controllers\ProductController::class, 'index']);

Router::get('/api/products/{id}', [\App\Controllers\ProductController::class, 'show']);

Router::post('/api/products', [\App\Controllers\ProductController::class, 'store'])
    ->middleware(AuthMiddleware::class);

Router::put('/api/products/{id}', [\App\Controllers\ProductController::class, 'update'])
    ->middleware(AuthMiddleware::class);

Router::delete('/api/products/{id}', [\App\Controllers\ProductController::class, 'destroy'])
    ->middleware(AuthMiddleware::class);

// ──────────────────────────────────────────
// Categories Routes
// ──────────────────────────────────────────
Router::get('/api/categories', [\App\Controllers\CategoryController::class, 'index']);

Router::post('/api/categories', [\App\Controllers\CategoryController::class, 'store'])
    ->middleware(AuthMiddleware::class);