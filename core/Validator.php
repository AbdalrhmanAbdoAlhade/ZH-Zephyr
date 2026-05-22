<?php

namespace Core;

class Validator
{
    /**
     * Validate data against rules
     * 
     * Rules format: 'field' => 'rule1|rule2|rule3'
     * Available rules:
     *   - required: Field must be present and not empty
     *   - email: Must be valid email format
     *   - numeric: Must be numeric
     *   - integer: Must be integer
     *   - string: Must be string
     *   - min:value: Minimum length or value
     *   - max:value: Maximum length or value
     *   - unique:table,column: Must be unique in database table
     *   - regex:pattern: Must match regex pattern
     *   - in:val1,val2,val3: Must be one of values
     *   - confirmed: Confirmation field (e.g., password_confirmed)
     */
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            $value = $data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                $error = self::validateRule($field, $value, $rule, $data);
                if ($error) {
                    if (!isset($errors[$field])) {
                        $errors[$field] = [];
                    }
                    $errors[$field][] = $error;
                }
            }
        }

        return $errors;
    }

    /**
     * Validate a single field against a rule
     */
    private static function validateRule(string $field, $value, string $rule, array $data): ?string
    {
        // Parse rule and parameters
        $parts = explode(':', $rule);
        $ruleName = trim($parts[0]);
        $param = $parts[1] ?? null;

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0' && $value !== 0) {
                    return "{$field} is required";
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    return "{$field} must be a valid email address";
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    return "{$field} must be numeric";
                }
                break;

            case 'integer':
                if (!empty($value) && !is_int($value) && !ctype_digit((string)$value)) {
                    return "{$field} must be an integer";
                }
                break;

            case 'string':
                if (!empty($value) && !is_string($value)) {
                    return "{$field} must be a string";
                }
                break;

            case 'min':
                if (!empty($value)) {
                    $min = (int)$param;
                    $length = is_numeric($value) ? (int)$value : strlen((string)$value);
                    if ($length < $min) {
                        return "{$field} must be at least {$min}";
                    }
                }
                break;

            case 'max':
                if (!empty($value)) {
                    $max = (int)$param;
                    $length = is_numeric($value) ? (int)$value : strlen((string)$value);
                    if ($length > $max) {
                        return "{$field} must not exceed {$max}";
                    }
                }
                break;

            case 'in':
                if (!empty($value)) {
                    $allowed = explode(',', $param);
                    $allowed = array_map('trim', $allowed);
                    if (!in_array((string)$value, $allowed)) {
                        return "{$field} must be one of: " . implode(', ', $allowed);
                    }
                }
                break;

            case 'regex':
                if (!empty($value) && !preg_match($param, (string)$value)) {
                    return "{$field} format is invalid";
                }
                break;

            case 'unique':
                if (!empty($value)) {
                    [$table, $column] = explode(',', $param);
                    $table = trim($table);
                    $column = trim($column);
                    
                    try {
                        $result = DB::query(
                            "SELECT COUNT(*) FROM {$table} WHERE {$column} = :value",
                            ['value' => $value]
                        )->fetchColumn();
                        
                        if ($result > 0) {
                            return "{$field} already exists";
                        }
                    } catch (\Exception $e) {
                        return "{$field} validation failed";
                    }
                }
                break;

            case 'confirmed':
                $confirmField = $field . '_confirmed';
                if ($value !== ($data[$confirmField] ?? null)) {
                    return "{$field} confirmation does not match";
                }
                break;
        }

        return null;
    }

    /**
     * Sanitize input data
     */
    public static function sanitize(array $data, array $rules = []): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                // Remove tags and trim
                $sanitized[$key] = trim(strip_tags($value));
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Check if validation passed
     */
    public static function passes(array $errors): bool
    {
        return empty($errors);
    }

    /**
     * Check if validation failed
     */
    public static function fails(array $errors): bool
    {
        return !empty($errors);
    }
}
