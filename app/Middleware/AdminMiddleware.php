<?php

declare(strict_types=1);

/**
 * Middleware web que permite acesso somente a usuários autenticados com perfil admin.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Middleware;

/**
 * Middleware web que permite acesso somente a usuários autenticados com perfil admin.
 */
class AdminMiddleware
{
    public static function handle(): void
    {
        if (guest()) {
            flash('error', 'Você precisa fazer login.');
            redirect('/login');
        }

        $user = auth();

        if (($user['role'] ?? 'user') !== 'admin') {
            flash('error', 'Acesso negado.');
            redirect('/dashboard');
        }
    }
}
