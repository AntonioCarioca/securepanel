<?php
/**
 * View da tela de login. A validação e criação de sessão ficam no AuthController; aqui ficam apenas os campos e links.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => 'Login', 'authUser' => $authUser ?? null]) ?>

<h1>Login</h1>

<form action="/login" method="POST">
    <?php $this->insert('components/csrf') ?>

    <div>
        <label for="email">E-mail</label><br>
        <input
            id="email"
            type="email"
            name="email"
            value="<?= $this->e(old_value('email')) ?>"
            required
        >
    </div>

    <br>

    <div>
        <label for="password">Senha</label><br>
        <input id="password" type="password" name="password" required>
    </div>

    <br>

    <button type="submit">Entrar</button>
</form>

<p><a href="/forgot-password">Esqueci minha senha</a></p>
<p><a href="/register">Criar conta</a></p>

<?php $this->insert('layouts/footer') ?>
