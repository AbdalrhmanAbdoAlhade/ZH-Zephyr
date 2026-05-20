<?php

namespace App\Controllers;

use Core\Request;
use Core\DB;

class ProductController extends Controller 
{
    public function index() 
    {
        $stmt = DB::query("SELECT id, name, price FROM products ORDER BY id DESC");
        $products = $stmt->fetchAll();

        $this->sendJson([
            "status" => true,
            "data" => $products
        ]);
    }

    public function store() 
    {
        $input = Request::getBody();

        if (empty($input['name']) || empty($input['price'])) {
            $this->sendJson([
                "status" => false,
                "message" => "Name and Price are required!"
            ], 400);
        }

        DB::query(
            "INSERT INTO products (name, price) VALUES (:name, :price)",
            [
                'name'  => $input['name'],
                'price' => $input['price']
            ]
        );

        $this->sendJson([
            "status" => true,
            "message" => "Product [{$input['name']}] saved into Database successfully!"
        ], 201);
    }
}