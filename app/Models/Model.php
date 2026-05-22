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
        
        // Validate column to prevent SQL injection
        if (!static::validateColumn($column)) {
            throw new \InvalidArgumentException("Column [{$column}] is not allowed");
        }

        return DB::query("SELECT * FROM {$table} WHERE {$column} = :value", ['value' => $value])->fetchAll();
    }

    /**
     * Get all allowed columns for this model
     * Returns all columns if not explicitly defined
     */
    protected static function getAllowedColumns(): array
    {
        // You can override in child models to restrict columns
        // Example: return ['id', 'name', 'email', 'created_at', 'updated_at'];
        try {
            $table = static::getTableName();
            $columns = DB::query("SHOW COLUMNS FROM {$table}")->fetchAll();
            return array_map(fn($col) => $col['Field'], $columns);
        } catch (\Exception) {
            return [];
        }
    }

    /**
     * Validate column name against whitelist
     */
    protected static function validateColumn(string $column): bool
    {
        $allowed = static::getAllowedColumns();
        return in_array($column, $allowed, true);
    }

    /**
     * Soft delete - sets deleted_at timestamp
     */
    public static function softDelete(int $id): bool
    {
        $table = static::getTableName();
        return DB::query(
            "UPDATE {$table} SET deleted_at = NOW() WHERE id = :id",
            ['id' => $id]
        )->rowCount() > 0;
    }

    /**
     * Get only non-deleted records (soft deletes)
     */
    public static function notDeleted(): array
    {
        $table = static::getTableName();
        return DB::query("SELECT * FROM {$table} WHERE deleted_at IS NULL ORDER BY id DESC")->fetchAll();
    }

    /**
     * Restore soft-deleted record
     */
    public static function restore(int $id): bool
    {
        $table = static::getTableName();
        return DB::query(
            "UPDATE {$table} SET deleted_at = NULL WHERE id = :id",
            ['id' => $id]
        )->rowCount() > 0;
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