<?php
/**
 * View da listagem de audit logs. Recebe logs já preparados por presenters e metadados do controller.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => 'Audit Logs', 'authUser' => $authUser ?? null]) ?>

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Segurança</p>
        <h2 class="mt-1 text-3xl font-bold tracking-tight text-slate-900">Audit Logs</h2>
        <p class="mt-2 text-sm text-slate-500">Monitore ações importantes realizadas no sistema.</p>
    </div>

    <a href="/dashboard" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Dashboard</a>
</div>

<section class="mb-6 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
    <form action="/audit-logs" method="GET" class="grid gap-4 lg:grid-cols-4 lg:items-end">
        <div class="lg:col-span-2">
            <label for="search" class="mb-2 block text-sm font-semibold text-slate-700">Buscar</label>
            <input id="search" type="text" name="search" value="<?= $this->e((string) ($search ?? '')) ?>" placeholder="Descrição, IP, nome ou e-mail"
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <div>
            <label for="action" class="mb-2 block text-sm font-semibold text-slate-700">Ação</label>
            <select id="action" name="action" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="">Todas</option>
                <?php foreach (($actions ?? []) as $item): ?>
                    <option value="<?= $this->e($item) ?>" <?= selected_attr($action ?? '', $item) ?>><?= $this->e($item) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="flex flex-wrap gap-2">
            <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">Aplicar</button>
            <a href="/audit-logs" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Limpar</a>
        </div>

        <div>
            <label for="date_from" class="mb-2 block text-sm font-semibold text-slate-700">Data inicial</label>
            <input id="date_from" type="date" name="date_from" value="<?= $this->e((string) ($dateFrom ?? '')) ?>"
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <div>
            <label for="date_to" class="mb-2 block text-sm font-semibold text-slate-700">Data final</label>
            <input id="date_to" type="date" name="date_to" value="<?= $this->e((string) ($dateTo ?? '')) ?>"
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <input type="hidden" name="sort" value="<?= $this->e((string) ($sort ?? 'created_at')) ?>">
        <input type="hidden" name="direction" value="<?= $this->e((string) ($direction ?? 'desc')) ?>">
    </form>
</section>

<div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-slate-500">Total de registros: <span class="font-semibold text-slate-900"><?= (int) ($total ?? 0) ?></span></p>
    <a href="<?= $this->e($exportUrl ?? '/audit-logs/export') ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">Exportar CSV</a>
</div>

<?php $this->insert('components/active-filters', ['filters' => $activeFilters ?? [], 'clearUrl' => '/audit-logs']) ?>

<section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500"><a class="hover:text-blue-600" href="<?= $this->e($sortUrls['id'] ?? '/audit-logs') ?>">ID<?= $this->e($sortIndicators['id'] ?? '') ?></a></th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500"><a class="hover:text-blue-600" href="<?= $this->e($sortUrls['action'] ?? '/audit-logs') ?>">Ação<?= $this->e($sortIndicators['action'] ?? '') ?></a></th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Usuário</th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Alvo</th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500">Descrição</th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500"><a class="hover:text-blue-600" href="<?= $this->e($sortUrls['ip_address'] ?? '/audit-logs') ?>">IP<?= $this->e($sortIndicators['ip_address'] ?? '') ?></a></th>
                    <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wide text-slate-500"><a class="hover:text-blue-600" href="<?= $this->e($sortUrls['created_at'] ?? '/audit-logs') ?>">Data<?= $this->e($sortIndicators['created_at'] ?? '') ?></a></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                <?php foreach ($logs as $log): ?>
                    <tr class="transition hover:bg-slate-50">
                        <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-700"><?= $log->id() ?></td>
                        <td class="whitespace-nowrap px-5 py-4">
                            <?php $this->insert('components/action-badge', ['badge' => $log->actionBadge()]) ?>
                            <div class="mt-1 text-xs text-slate-500"><?= $this->e($log->action()) ?></div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm">
                            <div class="font-semibold text-slate-900"><?= $this->e($log->userName()) ?></div>
                            <div class="text-xs text-slate-500"><?= $this->e($log->userEmail()) ?></div>
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-700"><?= $this->e($log->targetLabel()) ?></td>
                        <td class="max-w-md px-5 py-4 text-sm text-slate-600"><?= $this->e($log->description()) ?></td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600"><?= $this->e($log->ipAddress()) ?></td>
                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600"><?= $this->e($log->createdAt()) ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (count($logs) === 0): ?>
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-sm text-slate-500">Nenhum log encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php $this->insert('components/pagination', ['pagination' => $pagination ?? []]) ?>

<?php $this->insert('layouts/footer', ['authUser' => $authUser ?? null]) ?>
