<?php $this->insert('layouts/header', ['title' => 'Dashboard', 'authUser' => $authUser ?? null]) ?>

<h1>Dashboard</h1>

<p>Bem-vindo, <?= $this->e($dashboardUser['name']) ?>.</p>
<p>E-mail: <?= $this->e($dashboardUser['email']) ?></p>
<p>Perfil: <?= $this->e($dashboardUser['role']) ?></p>

<?php if ($dashboardUser['isAdmin']): ?>
    <p><a href="/users">Gerenciar usuários</a></p>
<?php endif; ?>

<form action="/logout" method="POST">
    <?php $this->insert('components/csrf') ?>
    <button type="submit">Sair</button>
</form>

<?php $this->insert('layouts/footer') ?>
