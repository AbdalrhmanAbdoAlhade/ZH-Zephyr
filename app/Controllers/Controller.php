<?php

namespace App\Controllers;

use Core\Response;

class Controller
{
    protected function sendJson(array $data, int $statusCode = 200): void
    {
        Response::json($data, $statusCode);
    }

    protected function success(mixed $data = null, string $message = 'OK', int $statusCode = 200): void
    {
        Response::success($data, $message, $statusCode);
    }

    protected function error(string $message, int $statusCode = 400, mixed $errors = null): void
    {
        Response::error($message, $statusCode, $errors);
    }

    protected function notFound(string $message = 'Resource not found'): void
    {
        Response::notFound($message);
    }
}