<?php

declare(strict_types=1);

/**
 * Controlador da página inicial protegida do sistema.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Controllers;

use App\Core\View;

/**
 * Controlador da página inicial protegida do sistema.
 */
class DashboardController
{
    /**
     * Redireciona a rota inicial para o dashboard ou login conforme autenticação.
     */
    public function home(): void
    {
        if (guest()) {
            redirect('/login');
        }

        redirect('/dashboard');
    }

    /**
     * Lista registros com filtros, paginação e dados preparados para a view.
     */
    public function index(): void
    {
        if (guest()) {
            redirect('/login');
        }

        View::render('dashboard/index', [
            'dashboardUser' => dashboard_user_data(auth()),
        ]);
    }
}
