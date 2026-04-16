<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci minha senha</title>
</head>
<body>
    <h1>Esqueci minha senha</h1>

    <?php require __DIR__ . '/../partials/alerts.php'; ?>

    <form action="/forgot-password" method="POST">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars(csrf_token()) ?>">

        <div>
            <label for="email">E-mail</label><br>
            <input
                id="email"
                type="email"
                name="email"
                value="<?= htmlspecialchars((string) old('email')) ?>"
                required
            >
        </div>

        <br>

        <button type="submit">Enviar link de recuperação</button>
    </form>

    <p><a href="/login">Voltar para login</a></p>
</body>
</html>