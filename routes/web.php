<?php

/**
 * DEFINIÇÃO DE ROTAS
 *
 * Cada rota define:
 * [método HTTP, URI, handler]
 *
 * Handler = [Controller::class, 'metodo']
 *
 * Essas rotas são usadas pelo FastRoute para mapear requisições.
 */

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\PasswordResetController;

return [
    // Autenticação
    ['GET', '/login', [AuthController::class, 'showLogin']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET', '/register', [AuthController::class, 'showRegister']],
    ['POST', '/register', [AuthController::class, 'register']],
    ['POST', '/logout', [AuthController::class, 'logout']],
    
    // Home / Dashboard
    ['GET', '/', [DashboardController::class, 'home']],
    ['GET', '/dashboard', [DashboardController::class, 'index']],
    
    // Recuperação de senha
    ['GET', '/forgot-password', [PasswordResetController::class, 'showForgotPassword']],
    ['POST', '/forgot-password', [PasswordResetController::class, 'sendResetLink']],
    ['GET', '/reset-password', [PasswordResetController::class, 'showResetPassword']],
    ['POST', '/reset-password', [PasswordResetController::class, 'resetPassword']],
];
