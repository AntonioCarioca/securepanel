<?php

declare(strict_types=1);

/**
 * Middleware da API que exige autenticação para retornar JSON padronizado.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Middleware;

use App\Core\ApiResponse;

/**
 * Middleware da API que exige autenticação para retornar JSON padronizado.
 */
class ApiAuthMiddleware
{
    public static function handle(): void
    {
        if (guest()) {
            ApiResponse::error('Não autenticado.', 401);
        }
    }
}
