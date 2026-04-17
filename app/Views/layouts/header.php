<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($title ?? 'Auth System') ?></title>
</head>
<body>
    <?php if (!empty($authUser)): ?>
        <?php $this->insert('partials/navbar', ['authUser' => $authUser]) ?>
    <?php endif; ?>

    <main>
        <?php $this->insert('partials/alerts') ?>