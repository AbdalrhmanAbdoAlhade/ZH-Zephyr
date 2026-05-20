<?php

namespace App\Models;

use Core\DB;

class Model 
{
    protected static string $table;

    // دالة ديناميكية لمعرفة اسم الجدول تلقائياً إذا لم يتم تحديده
    protected static function getTableName(): string 
    {
        if (isset(static::$table)) {
            return static::$table;
        }
        // لو اسم الكلاس Product، الجدول هيبقى products
        $className = (new \ReflectionClass(static::class))->getShortName();
        return strtolower($className) . 's';
    }

    // جلب كل البيانات: Product::all()
    public static function all(): array 
    {
        $table = self::getTableName();
        $stmt = DB::query("SELECT * FROM {$table} ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    // إدخال بيانات جديدة ديناميكياً: Product::create($data)
    public static function create(array $data): array 
    {
        $table = self::getTableName();
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        DB::query($sql, $data);

        // إرجاع البيانات اللي تم إدخالها مع الـ ID الجديد
        $data['id'] = DB::connect()->lastInsertId();
        return $data;
    }
}