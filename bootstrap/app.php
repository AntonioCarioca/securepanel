<?php

declare(strict_types=1);

/**
 * Arquivo de inicialização da aplicação: carrega Composer, .env, sessão, banco e helpers globais.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

/**
 * BOOTSTRAP DA APLICAÇÃO
 *
 * Responsabilidades:
 * - Carregar autoload do Composer
 * - Carregar helpers globais
 * - Iniciar sessão
 * - Carregar variáveis de ambiente (.env)
 * - Configurar banco de dados (Eloquent)
 * - Definir timezone
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Helpers/view.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rootPath = dirname(__DIR__);

$dotenv = Dotenv::createImmutable($rootPath);
$dotenv->safeLoad();

date_default_timezone_set('America/Manaus');

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

$capsule->setAsGlobal();
$capsule->bootEloquent();

/**
 * Lê uma configuração do .env com valor padrão opcional.
 */
function config(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

/**
 * Redireciona o navegador para outra rota e encerra a execução.
 */
function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

/**
 * Redireciona para a página anterior usando HTTP_REFERER.
 */
function back(): never
{
    $referer = $_SERVER['HTTP_REFERER'] ?? '/';
    header('Location: ' . $referer);
    exit;
}

/**
 * Salva uma mensagem temporária na sessão.
 */
function flash(string $key, mixed $value): void
{
    $_SESSION['_flash'][$key] = $value;
}

/**
 * Lê e remove uma mensagem temporária da sessão.
 */
function getFlash(string $key, mixed $default = null): mixed
{
    $value = $_SESSION['_flash'][$key] ?? $default;
    unset($_SESSION['_flash'][$key]);
    return $value;
}

/**
 * Recupera valor antigo de formulário após erro de validação.
 */
function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

/**
 * Retorna o usuário autenticado salvo na sessão.
 */
function auth(): ?array
{
    return $_SESSION['auth'] ?? null;
}

/**
 * Verifica se não existe usuário autenticado.
 */
function guest(): bool
{
    return !isset($_SESSION['auth']);
}

/**
 * Gera ou retorna o token CSRF da sessão.
 */
function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

/**
 * Compara o token enviado com o token salvo na sessão.
 */
function verify_csrf_token(?string $token): bool
{
    return isset($_SESSION['_csrf']) && is_string($token) && hash_equals($_SESSION['_csrf'], $token);
}
