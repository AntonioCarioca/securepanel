<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\ApiResponse;

class ApiAdminMiddleware
{
    public static function handle(): void
    {
        if (guest()) {
            ApiResponse::error('Não autenticado.', 401);
        }

        $user = auth();

        if (($user['role'] ?? 'user') !== 'admin') {
            ApiResponse::error('Acesso negado.', 403);
        }
    }
}
