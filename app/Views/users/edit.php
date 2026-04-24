<?php $this->insert('layouts/header', ['title' => $form['title'], 'authUser' => $authUser ?? null]) ?>

<h1><?= $this->e($form['title']) ?></h1>

<p><a href="/users">Voltar</a></p>

<?php $this->insert('users/form', ['form' => $form]) ?>

<?php $this->insert('layouts/footer') ?>
