<?php

require_once __DIR__ . '/vendor/autoload.php';

\Core\DotEnv::load(__DIR__);

if (php_sapi_name() !== 'cli') {
    die('This tool can only be run from the command line.');
}

$command = $argv[1] ?? null;
$argument = $argv[2] ?? null;

switch ($command) {

    case 'db:create':
        createDatabase();
        break;

    case 'db:migrate':
        migrateTables();
        break;

    case 'make:controller':
        if (!$argument) {
            echo "Usage: php zh.php make:controller <ControllerName>\n";
            exit(1);
        }
        makeController($argument);
        break;

    case 'make:model':
        if (!$argument) {
            echo "Usage: php zh.php make:model <ModelName>\n";
            exit(1);
        }
        makeModel($argument);
        break;

    case 'make:middleware':
        if (!$argument) {
            echo "Usage: php zh.php make:middleware <MiddlewareName>\n";
            exit(1);
        }
        makeMiddleware($argument);
        break;

    case 'vendor:publish':
        if (!$argument) {
            echo "Please specify the package provider class.\n";
            exit(1);
        }
        $provider = str_replace('/', '\\', $argument);
        if (class_exists($provider) && method_exists($provider, 'boot')) {
            $instance = new $provider();
            $instance->boot();
            echo "Package assets published successfully!\n";
        } else {
            echo "Provider class [{$provider}] not found or boot() method missing.\n";
        }
        break;

    default:
        echo "\n";
        echo "   ZH Mini-Backend CLI\n";
        echo "  ─────────────────────────────────────────\n";
        echo "  Database:\n";
        echo "    php zh.php db:create                  Create database from .env\n";
        echo "    php zh.php db:migrate                 Run all SQL migration files\n";
        echo "\n";
        echo "  Scaffolding:\n";
        echo "    php zh.php make:controller <Name>     Create a new Controller\n";
        echo "    php zh.php make:model <Name>          Create a new Model\n";
        echo "    php zh.php make:middleware <Name>     Create a new Middleware\n";
        echo "\n";
        echo "  Packages:\n";
        echo "    php zh.php vendor:publish <Provider>  Publish package assets\n";
        echo "\n";
        break;
}

// ──────────────────────────────────────────
// Database Commands
// ──────────────────────────────────────────

function createDatabase(): void
{
    $host   = env('DB_HOST', 'localhost');
    $user   = env('DB_USER', 'root');
    $pass   = env('DB_PASS', '');
    $dbname = env('DB_NAME', 'mini_backend_db');

    if (!$dbname) {
        die("Error: DB_NAME is not defined in your .env file.\n");
    }

    try {
        $pdo = new PDO("mysql:host={$host}", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        echo "Database [{$dbname}] created or verified successfully!\n";
    } catch (PDOException $e) {
        echo "Error creating database: " . $e->getMessage() . "\n";
    }
}

function migrateTables(): void
{
    $migrationsDir = __DIR__ . '/database/migrations';

    if (!is_dir($migrationsDir)) {
        die("Error: Migrations directory [database/migrations/] not found.\n");
    }

    $files = glob($migrationsDir . '/*.sql');

    if (empty($files)) {
        echo "No migration files (.sql) found to execute.\n";
        return;
    }

    echo "Running Migrations...\n";
    echo "──────────────────────────────────────────\n";

    foreach ($files as $file) {
        $fileName = basename($file);
        echo "  Migrating: {$fileName} ... ";

        try {
            $sql = file_get_contents($file);
            if (trim($sql) === '') {
                echo "Skipped (Empty File)\n";
                continue;
            }
            \Core\DB::query($sql);
            echo "Done\n";
        } catch (Exception $e) {
            echo "Failed\n";
            echo "  Error: " . $e->getMessage() . "\n";
            echo "──────────────────────────────────────────\n";
            die("Migration stopped due to an error.\n");
        }
    }

    echo "──────────────────────────────────────────\n";
    echo "All migrations executed successfully!\n";
}

// ──────────────────────────────────────────
// Scaffolding Commands
// ──────────────────────────────────────────

function makeController(string $name): void
{
    if (!str_ends_with($name, 'Controller')) {
        $name .= 'Controller';
    }

    $path = __DIR__ . "/app/Controllers/{$name}.php";

    if (file_exists($path)) {
        echo "Controller [{$name}] already exists.\n";
        exit(1);
    }

    $stub = <<<PHP
<?php

namespace App\Controllers;

use Core\Request;
use App\Controllers\Controller;

class {$name} extends Controller
{
    public function index(Request \$request): void
    {
        \$this->success([], 'OK');
    }

    public function show(Request \$request): void
    {
        \$id = \$request->param('id');
        \$this->success(['id' => \$id]);
    }

    public function store(Request \$request): void
    {
        \$data = \$request->body();
        \$this->success(\$data, 'Created', 201);
    }

    public function update(Request \$request): void
    {
        \$id   = \$request->param('id');
        \$data = \$request->body();
        \$this->success(\$data, 'Updated');
    }

    public function destroy(Request \$request): void
    {
        \$id = \$request->param('id');
        \$this->success(null, 'Deleted');
    }
}
PHP;

    file_put_contents($path, $stub);
    echo "Controller created: app/Controllers/{$name}.php\n";
}

function makeModel(string $name): void
{
    $path = __DIR__ . "/app/Models/{$name}.php";

    if (file_exists($path)) {
        echo "Model [{$name}] already exists.\n";
        exit(1);
    }

    $table = strtolower($name) . 's';

    $stub = <<<PHP
<?php

namespace App\Models;

class {$name} extends Model
{
    protected static string \$table = '{$table}';
}
PHP;

    file_put_contents($path, $stub);
    echo "Model created: app/Models/{$name}.php\n";
}

function makeMiddleware(string $name): void
{
    if (!str_ends_with($name, 'Middleware')) {
        $name .= 'Middleware';
    }

    $path = __DIR__ . "/app/Middleware/{$name}.php";

    if (file_exists($path)) {
        echo "Middleware [{$name}] already exists.\n";
        exit(1);
    }

    $stub = <<<PHP
<?php

namespace App\Middleware;

use Core\Response;

class {$name}
{
    public function handle(): void
    {
        // Add your middleware logic here
    }
}
PHP;

    file_put_contents($path, $stub);
    echo "Middleware created: app/Middleware/{$name}.php\n";
}
