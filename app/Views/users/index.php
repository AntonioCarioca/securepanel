<?php
/**
 * View da listagem de usuários. Filtros, URLs e presenters são preparados no UserController.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => 'Usuários', 'authUser' => $authUser ?? null]) ?>

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Administração</p>
        <h2 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Usuários</h2>
        <p class="mt-2 text-sm text-slate-500">Gerencie contas, perfis e exporte a base em CSV.</p>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="/dashboard" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Dashboard</a>
        <a href="/users/create" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Novo usuário</a>
    </div>
</div>

<section class="mb-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
    <form action="/users" method="GET" class="grid gap-4 lg:grid-cols-[1fr_220px_auto] lg:items-end">
        <div>
            <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">Buscar por nome ou e-mail</label>
            <input id="search" type="text" name="search" value="<?= $this->e((string) ($search ?? '')) ?>" placeholder="Digite o nome ou e-mail"
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <div>
            <label for="role" class="mb-2 block text-sm font-semibold text-slate-700">Perfil</label>
            <select id="role" name="role" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">Todos</option>
                <option value="user" <?= selected_attr($role ?? '', 'user') ?>>Usuário</option>
                <option value="admin" <?= selected_attr($role ?? '', 'admin') ?>>Admin</option>
            </select>
        </div>

        <input type="hidden" name="sort" value="<?= $this->e((string) ($sort ?? 'created_at')) ?>">
        <input type="hidden" name="direction" value="<?= $this->e((string) ($direction ?? 'desc')) ?>">

        <div class="flex flex-wrap gap-2">
            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Aplicar</button>
            <a href="/users" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Limpar</a>
        </div>
    </form>
</section>

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-slate-500">Total de registros: <span class="font-semibold text-slate-900"><?= (int) ($total ?? 0) ?></span></p>
    <a href="<?= $this->e($exportUrl ?? '/users/export') ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Exportar CSV</a>
</div>

<?php $this->insert('components/active-filters', ['filters' => $activeFilters ?? [], 'clearUrl' => '/users']) ?>

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">ID</th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500"><a class="hover:text-blue-600" href="<?= $this->e($sortUrls['name'] ?? '/users') ?>">Nome<?= $this->e($sortIndicators['name'] ?? '') ?></a></th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500"><a class="hover:text-blue-600" href="<?= $this->e($sortUrls['email'] ?? '/users') ?>">E-mail<?= $this->e($sortIndicators['email'] ?? '') ?></a></th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Perfil</th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500"><a class="hover:text-blue-600" href="<?= $this->e($sortUrls['created_at'] ?? '/users') ?>">Data<?= $this->e($sortIndicators['created_at'] ?? '') ?></a></th>
                    <th class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wide text-slate-500">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                <?php foreach ($users as $user): ?>
                    <tr class="transition hover:bg-slate-50">
                        <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-700"><?= $user->id() ?></td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-slate-900"><?= $this->e($user->name()) ?></td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600"><?= $this->e($user->email()) ?></td>
                        <td class="whitespace-nowrap px-5 py-4"><?php $this->insert('components/role-badge', ['badge' => $user->roleBadge()]) ?></td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600"><?= $this->e($user->createdAt()) ?></td>
                        <td class="whitespace-nowrap px-5 py-4 text-right text-sm">
                            <a href="<?= $this->e($user->editUrl()) ?>" class="font-semibold text-blue-600 hover:text-blue-700">Editar</a>
                            <form action="<?= $this->e($user->deleteUrl()) ?>" method="POST" class="ml-3 inline">
                                <?php $this->insert('components/csrf') ?>
                                <button type="submit" onclick="return confirm('Deseja excluir este usuário?')" class="font-semibold text-red-600 hover:text-red-700">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($users) === 0): ?>
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-slate-500">Nenhum usuário encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php $this->insert('components/pagination', ['pagination' => $pagination ?? []]) ?>

<?php $this->insert('layouts/footer', ['authUser' => $authUser ?? null]) ?>
