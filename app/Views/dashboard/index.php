<?php $this->insert('layouts/header', ['title' => 'Dashboard', 'authUser' => $authUser ?? null]) ?>

<h1>Dashboard</h1>

<p>Bem-vindo, <?= $this->e($user['name'] ?? 'Usuário') ?>.</p>
<p>E-mail: <?= $this->e($user['email'] ?? '') ?></p>
<p>Perfil: <?= $this->e($user['role'] ?? 'user') ?></p>

<?php if (($user['role'] ?? 'user') === 'admin'): ?>
    <p><a href="/users">Gerenciar usuários</a></p>
<?php endif; ?>

<form action="/logout" method="POST">
    <input type="hidden" name="_csrf" value="<?= $this->e(csrf_token()) ?>">
    <button type="submit">Sair</button>
</form>

<?php $this->insert('layouts/footer') ?>