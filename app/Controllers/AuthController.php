<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController
{
    public function showLogin(): void
    {
        if (!guest()) {
            redirect('/dashboard');
        }

        View::render('auth/login');
    }

    public function login(): void
    {
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

        unset($_SESSION['_old']);

        flash('success', 'Login realizado com sucesso.');
        redirect('/dashboard');
    }

    public function showRegister(): void
    {
        if (!guest()) {
            redirect('/dashboard');
        }

        View::render('auth/register');
    }

    public function register(): void
    {
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

    public function logout(): void
    {
        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        unset($_SESSION['auth']);

        session_regenerate_id(true);

        flash('success', 'Logout realizado com sucesso.');
        redirect('/login');
    }
}
