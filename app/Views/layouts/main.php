<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($title ?? 'Auth System') ?></title>
</head>
<body>
    <?php $this->insert('partials/navbar', ['authUser' => $authUser ?? null]) ?>

    <main>
        <?php $this->insert('partials/alerts') ?>
        <?= $this->section('contentt') ?>
    </main>
</body>
</html>