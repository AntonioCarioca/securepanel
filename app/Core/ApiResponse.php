<?php

declare(strict_types=1);

namespace App\Core;

class ApiResponse
{
    public static function send(
        bool $success,
        string $message,
        mixed $data = null,
        array $meta = [],
        int $statusCode = 200
    ): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            [
                'success' => $success,
                'message' => $message,
                'data' => $data,
                'meta' => $meta,
            ],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );

        exit;
    }

    public static function success(
        string $message = 'Requisição realizada com sucesso.',
        mixed $data = null,
        array $meta = [],
        int $statusCode = 200
    ): void {
        self::send(true, $message, $data, $meta, $statusCode);
    }

    public static function error(
        string $message = 'Ocorreu um erro.',
        int $statusCode = 400,
        mixed $data = null,
        array $meta = []
    ): void {
        self::send(false, $message, $data, $meta, $statusCode);
    }
}
