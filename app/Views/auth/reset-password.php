<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir senha</title>
</head>
<body>
    <h1>Redefinir senha</h1>

    <?php require __DIR__ . '/../partials/alerts.php'; ?>

    <form action="/reset-password" method="POST">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">

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
</body>
</html>