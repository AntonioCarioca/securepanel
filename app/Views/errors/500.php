<?php
/**
 * Página de erro 500 para falhas internas quando debug está desligado.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => '500', 'authUser' => $authUser ?? null]) ?>

<h1>500 - Erro interno</h1>
<p>Ocorreu um erro ao processar sua solicitação.</p>

<?php $this->insert('layouts/footer') ?>
