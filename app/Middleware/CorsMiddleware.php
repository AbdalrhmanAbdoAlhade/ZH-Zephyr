<<<<<<< HEAD
<?php

namespace App\Middleware;

use Core\Middleware;

class CorsMiddleware implements Middleware 
{
    public function handle(): bool 
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        return true;
    }
=======
<?php

namespace App\Middleware;

use Core\Middleware;

class CorsMiddleware implements Middleware 
{
    public function handle(): bool 
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        return true;
    }
>>>>>>> 1677249db46651c02f284a34ba822aec3bee5818
}