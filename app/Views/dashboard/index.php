<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>

    <?php require __DIR__ . '/../partials/alerts.php'; ?>

    <p>Bem-vindo, <?= htmlspecialchars($user['name'] ?? 'Usuário') ?>.</p>
    <p>E-mail: <?= htmlspecialchars($user['email'] ?? '') ?></p>
    <p>Perfil: <?= htmlspecialchars($user['role'] ?? 'user') ?></p>

    <?php if (($user['role'] ?? 'user') === 'admin'): ?>
        <p><a href="/users">Gerenciar usuários</a></p>
    <?php endif; ?>

    <form action="/logout" method="POST">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
        <button type="submit">Sair</button>
    </form>
</body>
</html>