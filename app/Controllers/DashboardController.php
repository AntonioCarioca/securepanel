<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class DashboardController
{
    public function home(): void
    {
        if (guest()) {
            redirect('/login');
        }

        redirect('/dashboard');
    }

    public function index(): void
    {
        if (guest()) {
            redirect('/login');
        }

        View::render('dashboard/index', [
            'user' => auth(),
        ]);
    }
}
