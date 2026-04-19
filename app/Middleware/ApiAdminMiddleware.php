<?php

declare(strict_types=1);

namespace App\Middleware;

class ApiAdminMiddleware
{
    public static function handle(): void
    {
        if (guest()) {
            self::jsonError('Não autenticado.', 401);
        }

        $user = auth();

        if (($user['role'] ?? 'user') !== 'admin') {
            self::jsonError('Acesso negado.', 403);
        }
    }

    private static function jsonError(string $message, int $statusCode): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode([
            'success' => false,
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        exit;
    }
}
