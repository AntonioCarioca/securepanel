<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo usuário</title>
</head>
<body>
    <h1>Novo usuário</h1>

    <?php require __DIR__ . '/../partials/alerts.php'; ?>

    <p><a href="/users">Voltar</a></p>

    <form action="/users" method="POST">
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
            <label for="role">Perfil</label><br>
            <select id="role" name="role">
                <option value="user" <?= old('role') === 'user' ? 'selected' : '' ?>>Usuário</option>
                <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <br>

        <button type="submit">Salvar</button>
    </form>
</body>
</html>