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
use App\Controllers\UserController;
use App\Controllers\AuditLogController;

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

    // Logs
    ['GET', '/audit-logs', [AuditLogController::class, 'index']],
    ['GET', '/audit-logs/export', [AuditLogController::class, 'exportCsv']],
    
    // Recuperação de senha
    ['GET', '/forgot-password', [PasswordResetController::class, 'showForgotPassword']],
    ['POST', '/forgot-password', [PasswordResetController::class, 'sendResetLink']],
    ['GET', '/reset-password', [PasswordResetController::class, 'showResetPassword']],
    ['POST', '/reset-password', [PasswordResetController::class, 'resetPassword']],

    // Usuário
    ['GET', '/users', [UserController::class, 'index']],
    ['GET', '/users/create', [UserController::class, 'showCreate']],
    ['GET', '/users/export', [UserController::class, 'exportCsv']],
    ['GET', '/users/{id:\d+}/edit', [UserController::class, 'showEdit']],
    ['POST', '/users', [UserController::class, 'store']],
    ['POST', '/users/{id:\d+}/update', [UserController::class, 'update']],
    ['POST', '/users/{id:\d+}/delete', [UserController::class, 'destroy']],
];
