<?php
/**
 * View de criação de usuário que chama o formulário compartilhado.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => $form['title'], 'authUser' => $authUser ?? null]) ?>

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Usuários</p>
        <h2 class="mt-1 text-3xl font-bold tracking-tight text-slate-900"><?= $this->e($form['title']) ?></h2>
        <p class="mt-2 text-sm text-slate-500">Cadastre uma nova conta para acesso ao sistema.</p>
    </div>

    <a href="/users" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Voltar</a>
</div>

<section class="max-w-3xl rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <?php $this->insert('users/form', ['form' => $form]) ?>
</section>

<?php $this->insert('layouts/footer', ['authUser' => $authUser ?? null]) ?>

