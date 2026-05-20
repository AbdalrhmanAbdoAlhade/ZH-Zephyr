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
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // تفعيل الأخطاء كـ Exceptions للأمان
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // جلب البيانات كمصفوفات associative
                    PDO::ATTR_EMULATE_PREPARES   => false,                  // الحماية من الـ SQL Injection الحقيقي
                ];

                self::$instance = new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                Response::json([
                    "status" => false,
                    "message" => "Database Connection Failed: " . $e->getMessage()
                ], 500);
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
}