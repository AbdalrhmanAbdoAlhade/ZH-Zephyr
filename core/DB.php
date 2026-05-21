<?php

namespace Core;

use PDO;
use PDOException;

class DB
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function connect(): PDO
    {
        if (self::$instance === null) {
            try {
                $config = require __DIR__ . '/../config/database.php';

                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                self::$instance = new PDO($dsn, $config['username'], $config['password'], $options);

            } catch (PDOException $e) {
                Response::json([
                    "status"  => false,
                    "message" => "Database Connection Failed: " . $e->getMessage()
                ], 500);
                exit;
            }
        }

        return self::$instance;
    }

    public static function query(string $sql, array $params = [])
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function reset(): void
    {
        self::$instance = null;
    }
}

class_alias(DB::class, 'Core\Database');
