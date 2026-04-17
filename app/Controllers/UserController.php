<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Middleware\AdminMiddleware;
use App\Models\User;
use Respect\Validation\Validator as v;

class UserController
{
    public function index(): void
    {
        AdminMiddleware::handle();

        $users = User::orderBy('id', 'desc')->get();

        View::render('users.index', [
            'users' => $users,
        ]);
    }

    public function showCreate(): void
    {
        AdminMiddleware::handle();

        View::render('users.create');
    }

    public function store(): void
    {
        AdminMiddleware::handle();

        $_SESSION['_old'] = $_POST;

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? 'user',
        ];

        $validator = v::key('name', v::stringType()->length(3, 100))
            ->key('email', v::email()->notEmpty())
            ->key('password', v::stringType()->length(6, null))
            ->key('role', v::in(['admin', 'user']));

        if (!$validator->validate($data)) {
            flash('error', 'Verifique os dados do formulário.');
            back();
        }

        if (User::where('email', $data['email'])->exists()) {
            flash('error', 'Este e-mail já está cadastrado.');
            back();
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'],
        ]);

        unset($_SESSION['_old']);

        flash('success', 'Usuário criado com sucesso.');
        redirect('/users');
    }

    public function showEdit(array $params): void
    {
        AdminMiddleware::handle();

        $id = (int) ($params['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            flash('error', 'Usuário não encontrado.');
            redirect('/users');
        }

        View::render('users.edit', [
            'editUser' => $user,
        ]);
    }

    public function update(array $params): void
    {
        AdminMiddleware::handle();

        $_SESSION['_old'] = $_POST;

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $id = (int) ($params['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            flash('error', 'Usuário não encontrado.');
            redirect('/users');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role' => $_POST['role'] ?? 'user',
        ];

        $validator = v::key('name', v::stringType()->length(3, 100))
            ->key('email', v::email()->notEmpty())
            ->key('role', v::in(['admin', 'user']));

        if (!$validator->validate($data)) {
            flash('error', 'Verifique os dados do formulário.');
            back();
        }

        $emailExists = User::where('email', $data['email'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            flash('error', 'Este e-mail já está em uso.');
            back();
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        $newPassword = trim($_POST['password'] ?? '');

        if ($newPassword !== '') {
            if (strlen($newPassword) < 6) {
                flash('error', 'A nova senha deve ter pelo menos 6 caracteres.');
                back();
            }

            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $user->update($updateData);

        unset($_SESSION['_old']);

        flash('success', 'Usuário atualizado com sucesso.');
        redirect('/users');
    }

    public function destroy(array $params): void
    {
        AdminMiddleware::handle();

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $id = (int) ($params['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            flash('error', 'Usuário não encontrado.');
            redirect('/users');
        }

        $currentUser = auth();

        if ((int) ($currentUser['id'] ?? 0) === (int) $user->id) {
            flash('error', 'Você não pode excluir seu próprio usuário.');
            redirect('/users');
        }

        $user->delete();

        flash('success', 'Usuário excluído com sucesso.');
        redirect('/users');
    }
}
