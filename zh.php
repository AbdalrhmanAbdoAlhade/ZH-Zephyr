<<<<<<< HEAD
<?php

require_once __DIR__ . '/vendor/autoload.php';
\Core\DotEnv::load(__DIR__);

if (php_sapi_name() !== 'cli') {
    die('Error: This tool can only be run from the command line.');
}

$command = $argv[1] ?? null;

switch ($command) {
    case 'db:create':
        createDatabase();
        break;

    case 'db:migrate':
        migrateTables();
        break;

    case 'db:fresh':
        echo "Warning: This will destroy all data and re-run all migrations!\n";
        dropAllTables();
        migrateTables();
        break;

    case 'db:rollback':
        $steps = 1;
        foreach ($argv as $arg) {
            if (str_starts_with($arg, '--steps=')) {
                $steps = (int) substr($arg, 8);
            }
        }
        rollbackMigrations($steps);
        break;

    case 'db:refresh':
        echo "Refreshing all migrations...\n";
        rollbackMigrations(999);
        migrateTables();
        break;

    case 'db:drop':
        dropDatabase();
        break;

    case 'app:optimize':
        optimizeApplication();
        break;

    case 'make:resource':
        $name = $argv[2] ?? null;
        if (!$name) {
            echo "Error: Please specify the resource name (e.g., Product, Category).\n";
            exit(1);
        }
        echo "Generating full resource files for [{$name}]...\n";
        echo "--------------------------------------------------\n";
        generateModelFile($name);
        generateControllerFile($name);
        generateMigrationFile($name);
        echo "--------------------------------------------------\n";
        echo "Success: All resource files generated successfully!\n";
        break;

    case 'vendor:publish':
        $provider = $argv[2] ?? null;
        if (!$provider) {
            echo "Error: Please specify the package provider class.\n";
            exit(1);
        }
        $provider = str_replace('/', '\\', $provider);
        if (class_exists($provider) && method_exists($provider, 'boot')) {
            $instance = new $provider();
            $instance->boot();
            echo "Success: Package assets published successfully!\n";
        } else {
            echo "Error: Provider class [{$provider}] not found or boot() method missing.\n";
        }
        break;

    default:
        echo "Available Commands:\n";
        echo "  php zh.php db:create            -> To create the database from .env configs\n";
        echo "  php zh.php db:migrate           -> To run all SQL files inside database/migrations/\n";
        echo "  php zh.php db:fresh             -> Drop all tables and re-run all migrations\n";
        echo "  php zh.php db:rollback          -> Rollback last migration. Use --steps=X for more steps\n";
        echo "  php zh.php db:refresh           -> Rollback all migrations and run them again\n";
        echo "  php zh.php db:drop              -> Drop the entire database completely\n";
        echo "  php zh.php app:optimize         -> Clear application caches and optimize composer autoload\n";
        echo "  php zh.php make:resource [N]    -> To generate Model, Controller & Migration in 1 step\n";
        echo "  php zh.php vendor:publish       -> To publish third-party package assets\n";
        break;
}

function createDatabase() {
    $host = env('DB_HOST', 'localhost');
    $user = env('DB_USER', 'root');
    $pass = env('DB_PASS', '');
    $dbname = env('DB_NAME', 'mini_backend_db');

    if (!$dbname) {
        die("Error: DB_NAME is not defined in your .env file.\n");
    }

    try {
        $pdo = new PDO("mysql:host={$host}", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        echo "Success: Database [{$dbname}] created or verified successfully!\n";
    } catch (PDOException $e) {
        echo "Error creating database: " . $e->getMessage() . "\n";
    }
}

function migrateTables() {
    $migrationsDir = __DIR__ . '/database/migrations';
    if (!is_dir($migrationsDir)) {
        die("Error: Migrations directory [database/migrations/] not found.\n");
    }

    $files = glob($migrationsDir . '/*.sql');
    if (empty($files)) {
        echo "No migration files (.sql) found to execute.\n";
        return;
    }

    sort($files);

    echo "Running Migrations...\n";
    echo "--------------------------------------------------\n";

    foreach ($files as $file) {
        $fileName = basename($file);
        echo "Migrating: {$fileName} ... ";

        try {
            $sql = file_get_contents($file);
            if (trim($sql) === '') {
                echo "Skipped (Empty File)\n";
                continue;
            }

            \Core\DB::query($sql);
            echo "Success\n";
        } catch (Exception $e) {
            echo "Failed\n";
            echo "Error Details: " . $e->getMessage() . "\n";
            echo "--------------------------------------------------\n";
            die("Error: Migration process stopped due to an error.\n");
        }
    }
    echo "--------------------------------------------------\n";
    echo "Success: All migrations executed successfully!\n";
}

function dropAllTables() {
    echo "Dropping all tables...\n";
    try {
        $dbname = env('DB_NAME');
        $tablesResult = \Core\DB::query("SHOW TABLES");
        $tables = $tablesResult->fetchAll(PDO::FETCH_COLUMN);

        if (empty($tables)) {
            echo "Database is already empty. No tables to drop.\n";
            return;
        }

        \Core\DB::query("SET FOREIGN_KEY_CHECKS = 0;");
        foreach ($tables as $table) {
            \Core\DB::query("DROP TABLE `{$table}`;");
            echo "Dropped table: {$table}\n";
        }
        \Core\DB::query("SET FOREIGN_KEY_CHECKS = 1;");
        echo "Success: All tables dropped successfully.\n";
    } catch (Exception $e) {
        die("Error while dropping tables: " . $e->getMessage() . "\n");
    }
}

function rollbackMigrations($steps) {
    $migrationsDir = __DIR__ . '/database/migrations';
    $files = glob($migrationsDir . '/*.sql');
    if (empty($files)) {
        echo "No migration files found.\n";
        return;
    }

    rsort($files);
    $executed = 0;

    echo "Rolling back migrations (Steps: {$steps})...\n";
    echo "--------------------------------------------------\n";

    \Core\DB::query("SET FOREIGN_KEY_CHECKS = 0;");
    foreach ($files as $file) {
        if ($executed >= $steps) {
            break;
        }

        $fileName = basename($file);
        if (preg_match('/create_(.*)_table/', $fileName, $matches)) {
            $tableName = $matches[1];
            try {
                \Core\DB::query("DROP TABLE IF EXISTS `{$tableName}`;");
                echo "Rolled back (Dropped table): {$tableName}\n";
                $executed++;
            } catch (Exception $e) {
                echo "Error: Failed to drop table [{$tableName}]: " . $e->getMessage() . "\n";
            }
        }
    }
    \Core\DB::query("SET FOREIGN_KEY_CHECKS = 1;");
    echo "--------------------------------------------------\n";
    echo "Success: Rollback completed for {$executed} tables!\n";
}

function dropDatabase() {
    $dbname = env('DB_NAME');
    if (!$dbname) {
        die("Error: DB_NAME is not defined in your .env file.\n");
    }

    echo "DANGER: Are you sure you want to completely DROP the database [{$dbname}]? (yes/no): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) != 'yes') {
        echo "Operation aborted.\n";
        exit;
    }

    try {
        \Core\DB::query("DROP DATABASE IF EXISTS `{$dbname}`;");
        echo "Success: Database [{$dbname}] has been completely deleted!\n";
    } catch (Exception $e) {
        echo "Error dropping database: " . $e->getMessage() . "\n";
    }
}

function optimizeApplication() {
    echo "Optimizing Application Engine...\n";
    echo "--------------------------------------------------\n";
    
    echo "Optimizing Composer Autoload Registry...\n";
    passthru('composer dump-autoload --optimize');

    $cacheDir = __DIR__ . '/storage/cache';
    if (is_dir($cacheDir)) {
        echo "Clearing storage cache...\n";
        $files = glob($cacheDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "Success: Cache cleared.\n";
    }

    echo "--------------------------------------------------\n";
    echo "Success: Application is fully optimized and running at maximum velocity!\n";
}

function generateModelFile($name) {
    $path = __DIR__ . "/app/Models/{$name}.php";
    if (file_exists($path)) {
        echo "Warning: Model [{$name}] already exists. Skipped.\n";
        return;
    }
    $tableName = getPluralName(strtolower($name));
    $template = "<?php\n\nnamespace App\Models;\n\nclass {$name} extends Model \n{\n    protected static string \$table = '{$tableName}';\n}\n";
    file_put_contents($path, $template);
    echo "Model generated: app/Models/{$name}.php\n";
}

function generateControllerFile($name) {
    $path = __DIR__ . "/app/Controllers/{$name}Controller.php";
    if (file_exists($path)) {
        echo "Warning: Controller [{$name}Controller] already exists. Skipped.\n";
        return;
    }
    $template = "<?php\n\nnamespace App\Controllers;\n\nuse Core\Request;\nuse Core\Response;\nuse App\Models\\{$name};\n\nclass {$name}Controller\n{\n    public function index(Request \$request): void\n    {\n        \$items = {$name}::all();\n        Response::json(\$items);\n    }\n\n    public function store(Request \$request): void\n    {\n        \$data = \$request->body();\n        if (empty(\$data)) {\n            Response::json(['error' => 'Data cannot be empty'], 400);\n            return;\n        }\n        \$item = {$name}::create(\$data);\n        Response::json(\$item, 201);\n    }\n}\n";
    file_put_contents($path, $template);
    echo "Controller generated: app/Controllers/{$name}Controller.php\n";
}

function generateMigrationFile($name) {
    $dir = __DIR__ . "/database/migrations";
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $timestamp = date('Y_m_d_His');
    $tableName = getPluralName(strtolower($name));
    $path = "{$dir}/{$timestamp}_create_{$tableName}_table.sql";
    $template = "CREATE TABLE IF NOT EXISTS `{$tableName}` (\n    `id` INT AUTO_INCREMENT PRIMARY KEY,\n    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n";
    file_put_contents($path, $template);
    echo "Migration generated: database/migrations/" . basename($path) . "\n";
}

function getPluralName($name) {
    if (str_ends_with($name, 'y')) {
        return substr($name, 0, -1) . 'ies';
    }
    return $name . 's';
=======
<?php

require_once __DIR__ . '/vendor/autoload.php';
\Core\DotEnv::load(__DIR__);

if (php_sapi_name() !== 'cli') {
    die('This tool can only be run from the command line.');
}

$command = $argv[1] ?? null;

switch ($command) {
    case 'db:create':
        createDatabase();
        break;

    case 'db:migrate':
        migrateTables();
        break;

    case 'vendor:publish':
        $provider = $argv[2] ?? null;
        if (!$provider) {
            echo "❌ Please specify the package provider class.\n";
            exit(1);
        }
        $provider = str_replace('/', '\\', $provider);
        if (class_exists($provider) && method_exists($provider, 'boot')) {
            $instance = new $provider();
            $instance->boot();
            echo "✔ Package assets published successfully!\n";
        } else {
            echo "❌ Provider class [{$provider}] not found or boot() method missing.\n";
        }
        break;

    default:
        echo "Available Commands:\n";
        echo "  php zh.php db:create       -> To create the database from .env configs\n";
        echo "  php zh.php db:migrate      -> To run all SQL files inside database/migrations/\n";
        echo "  php zh.php vendor:publish  -> To publish third-party package assets\n";
        break;
}

function createDatabase() {
    $host = env('DB_HOST', 'localhost');
    $user = env('DB_USER', 'root');
    $pass = env('DB_PASS', '');
    $dbname = env('DB_NAME', 'mini_backend_db');

    if (!$dbname) {
        die("❌ Error: DB_NAME is not defined in your .env file.\n");
    }

    try {
        $pdo = new PDO("mysql:host={$host}", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        echo "✔ Database [{$dbname}] created or verified successfully!\n";
    } catch (PDOException $e) {
        echo "❌ Error creating database: " . $e->getMessage() . "\n";
    }
}

function migrateTables() {
    $migrationsDir = __DIR__ . '/database/migrations';

    if (!is_dir($migrationsDir)) {
        die("❌ Error: Migrations directory [database/migrations/] not found.\n");
    }

    $files = glob($migrationsDir . '/*.sql');

    if (empty($files)) {
        echo "📭 No migration files (.sql) found to execute.\n";
        return;
    }

    echo "🚀 Running Migrations...\n";
    echo "--------------------------------------------------\n";

    foreach ($files as $file) {
        $fileName = basename($file);
        echo "Migrating: {$fileName} ... ";

        try {
            $sql = file_get_contents($file);

            if (trim($sql) === '') {
                echo "⚠️ Skipped (Empty File)\n";
                continue;
            }

            \Core\DB::query($sql);
            echo "✔ Success\n";

        } catch (Exception $e) {
            echo "❌ Failed\n";
            echo "Error Details: " . $e->getMessage() . "\n";
            echo "--------------------------------------------------\n";
            die("❌ Migration process stopped due to an error.\n");
        }
    }
    
    echo "--------------------------------------------------\n";
    echo "✔ All migrations executed successfully!\n";
>>>>>>> 1677249db46651c02f284a34ba822aec3bee5818
}