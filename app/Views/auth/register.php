<?php $this->insert('layouts/header', ['title' => 'Cadastro', 'authUser' => $authUser ?? null]) ?>
<h1>Cadastro</h1>

<form action="/register" method="POST">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

    <div>
        <label for="name">Nome</label><br>
        <input id="name" type="text" name="name" value="<?= htmlspecialchars((string) old('name')) ?>" required>
    </div>

    <br>

    <div>
        <label for="email">E-mail</label><br>
        <input id="email" type="email" name="email" value="<?= htmlspecialchars((string) old('email')) ?>" required>
    </div>

    <br>

    <div>
        <label for="password">Senha</label><br>
        <input id="password" type="password" name="password" required>
    </div>

    <br>

    <div>
        <label for="password_confirmation">Confirmar senha</label><br>
        <input id="password_confirmation" type="password" name="password_confirmation" required>
    </div>

    <br>

    <button type="submit">Cadastrar</button>
</form>

<p><a href="/login">Voltar para login</a></p>
<?php $this->insert('layouts/footer') ?>