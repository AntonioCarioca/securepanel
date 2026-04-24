<?php

declare(strict_types=1);

/**
 * Controlador responsável pelas telas e ações de autenticação web: login, cadastro e logout.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use Respect\Validation\Validator as v;
use App\Middleware\GuestMiddleware;
use App\Services\AuditLogService;

/**
 * Controlador responsável pelas telas e ações de autenticação web: login, cadastro e logout.
 */
class AuthController
{
    /**
     * Exibe a tela de login para visitantes.
     */
    public function showLogin(): void
    {
        GuestMiddleware::handle();

        View::render('auth/login');
    }

    /**
     * Processa o formulário de login, valida credenciais e cria a sessão do usuário.
     */
    public function login(): void
    {
        GuestMiddleware::handle();

        $_SESSION['_old'] = $_POST;

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $validator = v::key('email', v::email()->notEmpty())
            ->key('password', v::stringType()->notEmpty());

        if (!$validator->validate($_POST)) {
            flash('error', 'Preencha e-mail e senha corretamente.');
            back();
        }

        $user = User::where('email', $email)->first();

        if (!$user || !password_verify($password, $user->password)) {
            flash('error', 'Credenciais inválidas.');
            back();
        }

        session_regenerate_id(true);

        $_SESSION['auth'] = [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ];

        AuditLogService::log(
            'auth.login',
            (int) $user->id,
            'user',
            (int) $user->id,
            'Usuário realizou login no sistema.'
        );

        unset($_SESSION['_old']);

        flash('success', 'Login realizado com sucesso.');
        redirect('/dashboard');
    }

    /**
     * Exibe a tela de cadastro para visitantes.
     */
    public function showRegister(): void
    {
        GuestMiddleware::handle();

        View::render('auth/register');
    }

    /**
     * Processa o cadastro público de usuário comum.
     */
    public function register(): void
    {
        GuestMiddleware::handle();
        
        $_SESSION['_old'] = $_POST;

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirmation' => $_POST['password_confirmation'] ?? '',
        ];

        $validator = v::key('name', v::stringType()->length(3, 100))
            ->key('email', v::email()->notEmpty())
            ->key('password', v::stringType()->length(6, null))
            ->key('password_confirmation', v::equals($data['password']));

        if (!$validator->validate($data)) {
            flash('error', 'Verifique os dados do cadastro.');
            back();
        }

        $emailExists = User::where('email', $data['email'])->exists();

        if ($emailExists) {
            flash('error', 'Este e-mail já está cadastrado.');
            back();
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => 'user',
        ]);

        $_SESSION['auth'] = [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ];

        unset($_SESSION['_old']);

        flash('success', 'Conta criada com sucesso.');
        redirect('/dashboard');
    }

    /**
     * Finaliza a sessão do usuário autenticado.
     */
    public function logout(): void
    {
        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $currentUser = auth();

        if ($currentUser) {
            AuditLogService::log(
                'auth.logout',
                (int) $currentUser['id'],
                'user',
                (int) $currentUser['id'],
                'Usuário realizou logout do sistema.'
            );
        }

        unset($_SESSION['auth']);

        session_regenerate_id(true);

        flash('success', 'Logout realizado com sucesso.');
        redirect('/login');
    }
}
