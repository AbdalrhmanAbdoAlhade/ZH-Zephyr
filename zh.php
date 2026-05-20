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
}