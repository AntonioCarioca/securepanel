<?php
/**
 * View do dashboard. Os dados do usuário já chegam preparados pelo DashboardController.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => 'Dashboard', 'authUser' => $authUser ?? null]) ?>

<div class="mb-8">
    <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Bem-vindo</p>
    <h2 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Olá, <?= $this->e($dashboardUser['name']) ?> 👋</h2>
    <p class="mt-2 text-slate-500">Resumo rápido da sua conta e atalhos principais do sistema.</p>
</div>

<div class="grid gap-6 md:grid-cols-3">
    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-medium text-slate-500">E-mail</p>
        <p class="mt-2 break-all text-lg font-bold text-slate-900"><?= $this->e($dashboardUser['email']) ?></p>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Perfil</p>
        <p class="mt-2 text-lg font-bold uppercase text-slate-900"><?= $this->e($dashboardUser['role']) ?></p>
    </section>

    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-medium text-slate-500">Status</p>
        <p class="mt-2 text-lg font-bold text-emerald-600">Autenticado</p>
    </section>
</div>

<?php if ($dashboardUser['isAdmin']): ?>
    <div class="mt-8 grid gap-6 md:grid-cols-2">
        <a href="/users" class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
            <p class="text-sm font-semibold text-blue-600">Administração</p>
            <h3 class="mt-2 text-xl font-bold text-slate-900 group-hover:text-blue-700">Gerenciar usuários</h3>
            <p class="mt-2 text-sm text-slate-500">Crie, edite, filtre e exporte usuários do sistema.</p>
        </a>

        <a href="/audit-logs" class="group rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
            <p class="text-sm font-semibold text-blue-600">Segurança</p>
            <h3 class="mt-2 text-xl font-bold text-slate-900 group-hover:text-blue-700">Audit logs</h3>
            <p class="mt-2 text-sm text-slate-500">Acompanhe ações importantes realizadas no painel.</p>
        </a>
    </div>
<?php endif; ?>

<?php $this->insert('layouts/footer', ['authUser' => $authUser ?? null]) ?>
