<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <?php require __DIR__ . '/../partials/alerts.php'; ?>

    <form action="/login" method="POST">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

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

        <button type="submit">Entrar</button>
    </form>

    <p><a href="/forgot-password">Esqueci minha senha</a></p>
    <p><a href="/register">Criar conta</a></p>
</body>
</html>