<?php

namespace Core;

use Throwable;

class ErrorHandler
{
    /**
     * Register as global handler — call once in public/index.php
     */
    public static function register(): void
    {
        // Unhandled exceptions
        set_exception_handler([self::class, 'handleException']);

        // Fatal PHP errors
        set_error_handler([self::class, 'handleError']);

        // Shutdown — catch fatal errors (memory, parse, etc.)
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    // ──────────────────────────────────────────
    // Handlers
    // ──────────────────────────────────────────

    public static function handleException(Throwable $e): void
    {
        $debug = env('APP_DEBUG', 'false') === 'true';

        $body = [
            'status'  => false,
            'message' => $debug ? $e->getMessage() : 'An unexpected error occurred.',
        ];

        if ($debug) {
            $body['exception'] = get_class($e);
            $body['file']      = $e->getFile();
            $body['line']      = $e->getLine();
            $body['trace']     = explode("\n", $e->getTraceAsString());
        }

        self::send($body, 500);
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        // Respect @ operator (error suppression)
        if (!(error_reporting() & $errno)) {
            return false;
        }

        $debug = env('APP_DEBUG', 'false') === 'true';

        $body = [
            'status'  => false,
            'message' => $debug ? $errstr : 'An unexpected error occurred.',
        ];

        if ($debug) {
            $body['error_code'] = $errno;
            $body['file']       = $errfile;
            $body['line']       = $errline;
        }

        self::send($body, 500);

        return true;
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            $debug = env('APP_DEBUG', 'false') === 'true';

            $body = [
                'status'  => false,
                'message' => $debug ? $error['message'] : 'A fatal error occurred.',
            ];

            if ($debug) {
                $body['file'] = $error['file'];
                $body['line'] = $error['line'];
            }

            self::send($body, 500);
        }
    }

    // ──────────────────────────────────────────
    // Output
    // ──────────────────────────────────────────

    private static function send(array $body, int $statusCode): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code($statusCode);
        }

        echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }
}