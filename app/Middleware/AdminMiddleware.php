<?php

declare(strict_types=1);

namespace App\Middleware;

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
