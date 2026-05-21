<?php

namespace App\Models;

use Core\DB;

class Model
{
    protected static string $table;

    protected static function getTableName(): string
    {
        if (isset(static::$table)) {
            return static::$table;
        }
        $className = (new \ReflectionClass(static::class))->getShortName();
        return strtolower($className) . 's';
    }

    public static function all(): array
    {
        $table = static::getTableName();
        return DB::query("SELECT * FROM {$table} ORDER BY id DESC")->fetchAll();
    }

    public static function find(int $id): array|false
    {
        $table = static::getTableName();
        return DB::query("SELECT * FROM {$table} WHERE id = :id LIMIT 1", ['id' => $id])->fetch();
    }

    public static function where(string $column, mixed $value): array
    {
        $table = static::getTableName();
        return DB::query("SELECT * FROM {$table} WHERE {$column} = :value", ['value' => $value])->fetchAll();
    }

    public static function create(array $data): array
    {
        $table        = static::getTableName();
        $fields       = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        DB::query("INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})", $data);

        $data['id'] = DB::connect()->lastInsertId();
        return $data;
    }

    public static function update(int $id, array $data): bool
    {
        $table  = static::getTableName();
        $fields = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($data)));

        $data['id'] = $id;
        return DB::query("UPDATE {$table} SET {$fields} WHERE id = :id", $data)->rowCount() > 0;
    }

    public static function delete(int $id): bool
    {
        $table = static::getTableName();
        return DB::query("DELETE FROM {$table} WHERE id = :id", ['id' => $id])->rowCount() > 0;
    }

    public static function count(): int
    {
        $table = static::getTableName();
        return (int) DB::query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
    }
}