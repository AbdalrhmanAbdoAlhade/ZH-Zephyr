<?php

namespace App\Controllers;

use Core\Request;
use Core\Response;
use App\Models\Category;

class CategoryController
{
    public function index(Request $request): void
    {
        $items = Category::all();
        Response::json($items);
    }

    public function store(Request $request): void
    {
        $data = $request->body();
        
        if (empty($data)) {
            Response::json(['error' => 'Data cannot be empty'], 400);
            return;
        }

        $item = Category::create($data);
        Response::json($item, 201);
    }
}
