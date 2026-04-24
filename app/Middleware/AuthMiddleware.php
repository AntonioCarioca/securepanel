<?php

declare(strict_types=1);

/**
 * Middleware web que exige usuário autenticado.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Middleware;

/**
 * Middleware web que exige usuário autenticado.
 */
class AuthMiddleware
{
    public static function handle(): void
    {
        if (guest()) {
            flash('error', 'Você precisa fazer login.');
            redirect('/login');
        }
    }
}
