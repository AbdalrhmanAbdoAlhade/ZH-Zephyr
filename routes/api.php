<?php

use Core\Router;
use App\Controllers\ProductController;
use App\Middleware\AuthMiddleware;

Router::get('/api/v1/products', [ProductController::class, 'index']);

Router::post('/api/v1/products', [ProductController::class, 'store'])
      ->middleware(AuthMiddleware::class);