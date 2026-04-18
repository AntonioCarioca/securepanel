<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Mailer;
use App\Core\View;
use App\Models\PasswordReset;
use App\Models\User;
use App\Middleware\GuestMiddleware;
use Respect\Validation\Validator as v;

class PasswordResetController
{
    public function showForgotPassword(): void
    {
        GuestMiddleware::handle();

        View::render('auth/forgot-password');
    }

    public function sendResetLink(): void
    {
        GuestMiddleware::handle();
        
        $_SESSION['_old'] = $_POST;

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $email = trim($_POST['email'] ?? '');

        $validator = v::email()->notEmpty();

        if (!$validator->validate($email)) {
            flash('error', 'Informe um e-mail válido.');
            back();
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            flash('success', 'Se o e-mail existir, enviaremos um link de recuperação.');
            back();
        }

        PasswordReset::where('email', $email)->delete();

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        PasswordReset::create([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $resetLink = rtrim((string) config('APP_URL', ''), '/') . '/reset-password?token=' . urlencode($token);

        $body = "
            <h1>Redefinição de senha</h1>
            <p>Olá, {$user->name}.</p>
            <p>Clique no link abaixo para redefinir sua senha:</p>
            <p><a href=\"{$resetLink}\">Redefinir senha</a></p>
            <p>Este link expira em 1 hora.</p>
        ";

        $mailer = new Mailer();
        $sent = $mailer->send($email, 'Redefinição de senha', $body);

        unset($_SESSION['_old']);

        if (!$sent) {
            flash('error', 'Não foi possível enviar o e-mail de recuperação.');
            back();
        }

        flash('success', 'Enviamos o link de recuperação para seu e-mail.');
        redirect('/login');
    }

    public function showResetPassword(): void
    {
        GuestMiddleware::handle();

        $token = $_GET['token'] ?? '';

        if (!is_string($token) || trim($token) === '') {
            flash('error', 'Token inválido.');
            redirect('/forgot-password');
        }

        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset) {
            flash('error', 'Token não encontrado.');
            redirect('/forgot-password');
        }

        if (strtotime($passwordReset->expires_at) < time()) {
            $passwordReset->delete();
            flash('error', 'Token expirado.');
            redirect('/forgot-password');
        }

        View::render('auth/reset-password', [
            'token' => $token,
        ]);
    }

    public function resetPassword(): void
    {
        GuestMiddleware::handle();

        $_SESSION['_old'] = $_POST;

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        if (!is_string($token) || trim($token) === '') {
            flash('error', 'Token inválido.');
            back();
        }

        $validator = v::key('password', v::stringType()->length(6, null))
            ->key('password_confirmation', v::equals($password));

        if (!$validator->validate($_POST)) {
            flash('error', 'Verifique os dados informados.');
            back();
        }

        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset) {
            flash('error', 'Token não encontrado.');
            back();
        }

        if (strtotime($passwordReset->expires_at) < time()) {
            $passwordReset->delete();
            flash('error', 'Token expirado.');
            back();
        }

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            flash('error', 'Usuário não encontrado.');
            back();
        }

        $user->update([
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $passwordReset->delete();

        unset($_SESSION['_old']);

        flash('success', 'Senha redefinida com sucesso. Faça login.');
        redirect('/login');
    }
}
