<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuário</title>
</head>
<body>
    <h1>Editar usuário</h1>

    <?php require __DIR__ . '/../partials/alerts.php'; ?>

    <p><a href="/users">Voltar</a></p>

    <form action="/users/<?= (int) $editUser->id ?>/update" method="POST">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

        <div>
            <label for="name">Nome</label><br>
            <input
                id="name"
                type="text"
                name="name"
                value="<?= htmlspecialchars((string) old('name', $editUser->name)) ?>"
                required
            >
        </div>

        <br>

        <div>
            <label for="email">E-mail</label><br>
            <input
                id="email"
                type="email"
                name="email"
                value="<?= htmlspecialchars((string) old('email', $editUser->email)) ?>"
                required
            >
        </div>

        <br>

        <div>
            <label for="password">Nova senha (opcional)</label><br>
            <input id="password" type="password" name="password">
        </div>

        <br>

        <div>
            <label for="role">Perfil</label><br>
            <select id="role" name="role">
                <?php $oldRole = old('role', $editUser->role); ?>
                <option value="user" <?= $oldRole === 'user' ? 'selected' : '' ?>>Usuário</option>
                <option value="admin" <?= $oldRole === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <br>

        <button type="submit">Atualizar</button>
    </form>
</body>
</html>