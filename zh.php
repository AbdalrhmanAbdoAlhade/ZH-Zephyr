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

    case 'generate:secret':
        generateSecret();
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
        echo "  Security:\n";
        echo "    php zh.php generate:secret             Generate & set JWT secrets in .env\n";
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
        echo "  Database [{$dbname}] created successfully!\n";
    } catch (PDOException $e) {
        echo "Error creating database: " . $e->getMessage() . "\n";
        exit(1);
    }
}

function migrateTables(): void
{
    $host   = env('DB_HOST', 'localhost');
    $user   = env('DB_USER', 'root');
    $pass   = env('DB_PASS', '');
    $dbname = env('DB_NAME', 'mini_backend_db');

    // ── Step 1: Check if the database exists ──
    try {
        new PDO("mysql:host={$host};dbname={$dbname}", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
    } catch (PDOException $e) {
        // MySQL error 1049 = Unknown database
        if (str_contains($e->getMessage(), '1049') || str_contains($e->getMessage(), 'Unknown database')) {
            echo "\n";
            echo "  ⚠  Database [{$dbname}] does not exist.\n";
            echo "  Would you like to create it now? [yes/no]: ";

            $answer = strtolower(trim(fgets(STDIN)));

            if (in_array($answer, ['yes', 'y'])) {
                createDatabase();
                // Small pause to let MySQL register the new DB
                sleep(1);
            } else {
                echo "\n  Migration aborted. Database was not created.\n";
                exit(1);
            }
        } else {
            echo "  Error connecting to MySQL: " . $e->getMessage() . "\n";
            exit(1);
        }
    }

    // ── Step 2: Run migration files ──
    $migrationsDir = __DIR__ . '/database/migrations';

    if (!is_dir($migrationsDir)) {
        die("Error: Migrations directory [database/migrations/] not found.\n");
    }

    $files = glob($migrationsDir . '/*.sql');

    if (empty($files)) {
        echo "No migration files (.sql) found to execute.\n";
        return;
    }

    echo "\nRunning Migrations...\n";
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

            // Split into individual statements and skip USE / empty ones
            $statements = array_filter(
                array_map('trim', explode(';', $sql)),
                fn($s) => $s !== '' && !preg_match('/^\s*USE\s+/i', $s)
            );

            foreach ($statements as $statement) {
                \Core\DB::query($statement);
            }

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

use Core\Middleware;

class {$name} implements Middleware
{
    public function handle(): bool
    {
        // TODO: Add your middleware logic here
        // Return true to continue, false to abort
        return true;
    }
}
PHP;

    file_put_contents($path, $stub);
    echo "Middleware created: app/Middleware/{$name}.php\n";
}

// ──────────────────────────────────────────
// Security Commands
// ──────────────────────────────────────────

function generateSecret(): void
{
    $envPath = __DIR__ . '/.env';

    if (!file_exists($envPath)) {
        echo "  Error: .env file not found.\n";
        exit(1);
    }

    // Keys to generate
    $keys = [
        'JWT_SECRET',
        'APP_SECRET',
    ];

    $envContent = file_get_contents($envPath);
    $generated  = [];
    $skipped    = [];

    foreach ($keys as $key) {
        $secret = bin2hex(random_bytes(32)); // 64-char hex = 256-bit

        if (preg_match('/^' . $key . '=(.*)$/m', $envContent, $matches)) {
            $existing = trim($matches[1]);

            // Already has a real value (not a placeholder)
            if ($existing !== '' && !str_contains($existing, 'your_') && !str_contains($existing, 'generate_with')) {
                $skipped[] = $key;
                continue;
            }

            // Replace existing placeholder
            $envContent = preg_replace(
                '/^' . $key . '=.*$/m',
                $key . '=' . $secret,
                $envContent
            );
        } else {
            // Key doesn't exist → append it
            $envContent .= "\n{$key}={$secret}";
        }

        $generated[] = $key;
    }

    file_put_contents($envPath, $envContent);

    echo "\n";
    echo "  ──────────────────────────────────────────\n";

    foreach ($generated as $key) {
        echo "  ✔  {$key} generated and saved.\n";
    }

    foreach ($skipped as $key) {
        echo "  ⚠  {$key} already has a value — skipped. Use --force to override.\n";
    }

    if (empty($generated)) {
        echo "  Nothing was changed.\n";
    }

    echo "  ──────────────────────────────────────────\n\n";

    // Handle --force flag: regenerate even existing keys
    global $argv;
    if (in_array('--force', $argv) && !empty($skipped)) {
        echo "  Running with --force, regenerating skipped keys...\n";
        $envContent = file_get_contents($envPath);
        foreach ($skipped as $key) {
            $secret     = bin2hex(random_bytes(32));
            $envContent = preg_replace('/^' . $key . '=.*$/m', $key . '=' . $secret, $envContent);
            echo "  ✔  {$key} regenerated.\n";
        }
        file_put_contents($envPath, $envContent);
        echo "\n";
    }
}
