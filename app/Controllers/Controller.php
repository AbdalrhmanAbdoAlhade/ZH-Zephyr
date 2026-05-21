<?php

namespace App\Controllers;

use Core\Response;

class Controller 
{
    protected function sendJson(array $data, int $statusCode = 200): void 
    {
        Response::json($data, $statusCode);
    }
}