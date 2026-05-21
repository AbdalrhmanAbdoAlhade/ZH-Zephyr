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

        // تحقق سريع
        if (empty($data['name']) || empty($data['price'])) {
            Response::json(["message" => "Name and Price are required!"], 400);
        }

        $product = Product::create([
            'name'  => $data['name'],
            'price' => $data['price']
        ]);

        Response::json($product, 201);
    }
}