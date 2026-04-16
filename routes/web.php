<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;

return [
    ['GET', '/', [DashboardController::class, 'home']],
    ['GET', '/login', [AuthController::class, 'showLogin']],
    ['POST', '/login', [AuthController::class, 'login']],
    ['GET', '/register', [AuthController::class, 'showRegister']],
    ['POST', '/register', [AuthController::class, 'register']],
    ['POST', '/logout', [AuthController::class, 'logout']],
    ['GET', '/dashboard', [DashboardController::class, 'index']],
];
