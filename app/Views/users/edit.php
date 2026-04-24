<?php
/**
 * View de edição de usuário que chama o formulário compartilhado.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => $form['title'], 'authUser' => $authUser ?? null]) ?>

<h1><?= $this->e($form['title']) ?></h1>

<p><a href="/users">Voltar</a></p>

<?php $this->insert('users/form', ['form' => $form]) ?>

<?php $this->insert('layouts/footer') ?>
