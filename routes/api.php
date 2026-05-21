<<<<<<< HEAD
<?php

use Core\Router;
use App\Controllers\ProductController;
use App\Middleware\AuthMiddleware;

Router::get('/api/v1/products', [ProductController::class, 'index']);

Router::post('/api/v1/products', [ProductController::class, 'store'])
=======
<?php

use Core\Router;
use App\Controllers\ProductController;
use App\Middleware\AuthMiddleware;

Router::get('/api/v1/products', [ProductController::class, 'index']);

Router::post('/api/v1/products', [ProductController::class, 'store'])
>>>>>>> 1677249db46651c02f284a34ba822aec3bee5818
      ->middleware(AuthMiddleware::class);