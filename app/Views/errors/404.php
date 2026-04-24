<?php $this->insert('layouts/header', ['title' => '404', 'authUser' => $authUser ?? null]) ?>

<h1>404 - Página não encontrada</h1>
<p>A rota que você tentou acessar não existe.</p>

<?php $this->insert('layouts/footer') ?>
