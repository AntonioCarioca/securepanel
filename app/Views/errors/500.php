<?php $this->insert('layouts/header', ['title' => '500', 'authUser' => $authUser ?? null]) ?>

<h1>500 - Erro interno</h1>
<p>Ocorreu um erro ao processar sua solicitação.</p>

<?php $this->insert('layouts/footer') ?>
