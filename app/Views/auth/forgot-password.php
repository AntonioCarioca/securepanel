<?php $this->insert('layouts/header', ['title' => 'Esqueci minha senha', 'authUser' => $authUser ?? null]) ?>

<h1>Esqueci minha senha</h1>

<form action="/forgot-password" method="POST">
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

    <button type="submit">Enviar link de recuperação</button>
</form>

<p><a href="/login">Voltar para login</a></p>

<?php $this->insert('layouts/footer') ?>
