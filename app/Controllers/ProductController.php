<?php

namespace App\Controllers;

use Core\Request;
use Core\Response;
use App\Models\Product;

class ProductController
{
    // جلب المنتجات عبر الـ Model
    public function index(Request $request): void
    {
        $products = Product::all();
        Response::json($products);
    }

    // تخزين منتج جديد
public function store(Request $request): void
{
    $data = $request->body();
    
    $errors = Validator::validate($data, [
        'name'  => 'required|string|min:3|max:255',
        'price' => 'required|numeric|min:0.01',
    ]);
    
    if ($errors) {
        Response::validationError($errors);
        return;
    }
    
    try {
        $product = Product::create($data);
        Response::success($product, 'Product created', 201);
    } catch (Exception $e) {
        Response::error('Failed to create product', 500);
    }
}
}