<?php

declare(strict_types=1);

namespace App\Middleware;

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
