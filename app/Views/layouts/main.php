<?php
/**
 * Layout base usado quando uma view utiliza herança do Plates.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
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
        <?= $this->section('content') ?>
    </main>
</body>
</html>
