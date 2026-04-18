<?php

declare(strict_types=1);

namespace App\Middleware;

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
