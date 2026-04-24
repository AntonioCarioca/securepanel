<?php $this->insert('layouts/header', ['title' => 'Redefinir senha', 'authUser' => $authUser ?? null]) ?>

<h1>Redefinir senha</h1>

<form action="/reset-password" method="POST">
    <?php $this->insert('components/csrf') ?>
    <input type="hidden" name="token" value="<?= $this->e((string) ($token ?? '')) ?>">

    <div>
        <label for="password">Nova senha</label><br>
        <input id="password" type="password" name="password" required>
    </div>

    <br>

    <div>
        <label for="password_confirmation">Confirmar nova senha</label><br>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
    </div>

    <br>

    <button type="submit">Salvar nova senha</button>
</form>

<p><a href="/login">Voltar para login</a></p>

<?php $this->insert('layouts/footer') ?>
