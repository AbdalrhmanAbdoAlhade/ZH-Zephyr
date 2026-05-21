<<<<<<< HEAD
<?php

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
    }
=======
<?php

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
    }
>>>>>>> 1677249db46651c02f284a34ba822aec3bee5818
}