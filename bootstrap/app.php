<?php

declare(strict_types=1);

/**
 * BOOTSTRAP DA APLICAÇÃO
 *
 * Este arquivo é responsável por preparar o ambiente da aplicação.
 * Ele NÃO executa lógica de negócio.
 *
 * Responsabilidades:
 * - Carregar autoload do Composer
 * - Iniciar sessão
 * - Carregar variáveis de ambiente (.env)
 * - Configurar banco de dados (Eloquent)
 * - Definir timezone
 * - Disponibilizar helpers globais
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rootPath = dirname(__DIR__);

// Carrega .env
$dotenv = Dotenv::createImmutable($rootPath);
$dotenv->safeLoad();

// Configura timezone
date_default_timezone_set('America/Manaus');

// Configura Eloquent ORM
$capsule = new Capsule();

$capsule->addConnection([
    'driver'    => $_ENV['DB_CONNECTION'] ?? 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? 'mysql',
    'port'      => (int) ($_ENV['DB_PORT'] ?? 3306),
    'database'  => $_ENV['DB_DATABASE'] ?? '',
    'username'  => $_ENV['DB_USERNAME'] ?? '',
    'password'  => $_ENV['DB_PASSWORD'] ?? '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// Torna o Capsule disponível globalmente através de métodos estáticos
$capsule->setAsGlobal();
// Inicializa o Eloquent
$capsule->bootEloquent();

// Funções helper simples
function config(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function back(): never
{
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    header('Location: ' . $referer);
    exit;
}

function flash(string $key, mixed $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

function getFlash(string $key, mixed $default = null): mixed
{
    $value = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function auth(): ?array
{
    return $_SESSION['auth'] ?? null;
}

function guest(): bool
{
    return !isset($_SESSION['auth']);
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function verify_csrf_token(?string $token): bool
{
    return isset($_SESSION['_csrf']) && is_string($token) && hash_equals($_SESSION['_csrf'], $token);
}
