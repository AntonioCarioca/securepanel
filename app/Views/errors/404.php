<?php
/**
 * Página de erro 404 para rotas web inexistentes.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => '404', 'authUser' => $authUser ?? null]) ?>

<section class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
    <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Erro 404</p>
    <h2 class="mt-3 text-3xl font-bold text-slate-900">Página não encontrada</h2>
    <p class="mx-auto mt-3 max-w-md text-slate-500">A rota que você tentou acessar não existe ou foi removida.</p>
    <a href="/dashboard" class="mt-6 inline-flex rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Voltar ao dashboard</a>
</section>

<?php $this->insert('layouts/footer', ['authUser' => $authUser ?? null]) ?>
