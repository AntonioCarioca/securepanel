<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
</head>
<body>
    <h1>Usuários</h1>

    <?php require __DIR__ . '/../partials/alerts.php'; ?>

    <p>
        <a href="/dashboard">Voltar ao dashboard</a> |
        <a href="/users/create">Novo usuário</a>
    </p>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= (int) $user->id ?></td>
                    <td><?= htmlspecialchars($user->name) ?></td>
                    <td><?= htmlspecialchars($user->email) ?></td>
                    <td><?= htmlspecialchars($user->role) ?></td>
                    <td>
                        <a href="/users/<?= (int) $user->id ?>/edit">Editar</a>

                        <form action="/users/<?= (int) $user->id ?>/delete" method="POST" style="display:inline;">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
                            <button type="submit" onclick="return confirm('Deseja excluir este usuário?')">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>