<?php

declare(strict_types=1);

/**
 * Middleware web que impede usuários já autenticados de acessar telas públicas como login e cadastro.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Middleware;

/**
 * Middleware web que impede usuários já autenticados de acessar telas públicas como login e cadastro.
 */
class GuestMiddleware
{
    public static function handle(): void
    {
        if (!guest()) {
            flash('error', 'Você já está autenticado.');
            redirect('/dashboard');
        }
    }
}
