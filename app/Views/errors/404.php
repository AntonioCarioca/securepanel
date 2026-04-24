<?php
/**
 * Página de erro 404 para rotas web inexistentes.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => '404', 'authUser' => $authUser ?? null]) ?>

<h1>404 - Página não encontrada</h1>
<p>A rota que você tentou acessar não existe.</p>

<?php $this->insert('layouts/footer') ?>
