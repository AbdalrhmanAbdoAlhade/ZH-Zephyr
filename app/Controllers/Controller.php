<<<<<<< HEAD
<?php

namespace App\Controllers;

use Core\Response;

class Controller 
{
    protected function sendJson(array $data, int $statusCode = 200): void 
    {
        Response::json($data, $statusCode);
    }
=======
<?php

namespace App\Controllers;

use Core\Response;

class Controller 
{
    protected function sendJson(array $data, int $statusCode = 200): void 
    {
        Response::json($data, $statusCode);
    }
>>>>>>> 1677249db46651c02f284a34ba822aec3bee5818
}