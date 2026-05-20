<?php

use Core\Router;
use App\Controllers\ProductController;
use App\Middleware\AuthMiddleware;

// مسار جلب المنتجات (متاح للكل عادي - Public)
Router::get('/api/v1/products', [ProductController::class, 'index']);

// مسار إضافة منتج جديد (محمي - لازم يكون معاه Token)
Router::post('/api/v1/products', [ProductController::class, 'store'])
      ->middleware(AuthMiddleware::class);