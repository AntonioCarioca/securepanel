<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\ApiResponse;

class ApiAuthMiddleware
{
    public static function handle(): void
    {
        if (guest()) {
            ApiResponse::error('Não autenticado.', 401);
        }
    }
}
