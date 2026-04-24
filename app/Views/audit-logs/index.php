<?php $this->insert('layouts/header', ['title' => 'Audit Logs', 'authUser' => $authUser ?? null]) ?>

<h1>Audit Logs</h1>

<p><a href="/dashboard">Voltar ao dashboard</a></p>

<form action="/audit-logs" method="GET" style="margin-bottom: 20px;">
    <div style="margin-bottom: 12px;">
        <label for="search">Buscar</label><br>
        <input
            id="search"
            type="text"
            name="search"
            value="<?= $this->e((string) ($search ?? '')) ?>"
            placeholder="Descrição, IP, nome ou e-mail"
        >
    </div>

    <div style="margin-bottom: 12px;">
        <label for="action">Ação</label><br>
        <select id="action" name="action">
            <option value="">Todas</option>
            <?php foreach (($actions ?? []) as $item): ?>
                <option value="<?= $this->e($item) ?>" <?= selected_attr($action ?? '', $item) ?>>
                    <?= $this->e($item) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="margin-bottom: 12px;">
        <label for="date_from">Data inicial</label><br>
        <input id="date_from" type="date" name="date_from" value="<?= $this->e((string) ($dateFrom ?? '')) ?>">
    </div>

    <div style="margin-bottom: 12px;">
        <label for="date_to">Data final</label><br>
        <input id="date_to" type="date" name="date_to" value="<?= $this->e((string) ($dateTo ?? '')) ?>">
    </div>

    <input type="hidden" name="sort" value="<?= $this->e((string) ($sort ?? 'created_at')) ?>">
    <input type="hidden" name="direction" value="<?= $this->e((string) ($direction ?? 'desc')) ?>">

    <button type="submit">Aplicar</button>
    <a href="/audit-logs" style="margin-left: 10px;">Limpar filtros</a>
</form>

<p style="margin-bottom: 16px;">
    <a href="<?= $this->e($exportUrl ?? '/audit-logs/export') ?>">Exportar CSV</a>
</p>

<?php $this->insert('components/active-filters', ['filters' => $activeFilters ?? [], 'clearUrl' => '/audit-logs']) ?>

<p>Total de registros: <?= (int) ($total ?? 0) ?></p>

<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th><a href="<?= $this->e($sortUrls['id'] ?? '/audit-logs') ?>">ID<?= $this->e($sortIndicators['id'] ?? '') ?></a></th>
            <th><a href="<?= $this->e($sortUrls['action'] ?? '/audit-logs') ?>">Ação<?= $this->e($sortIndicators['action'] ?? '') ?></a></th>
            <th>Usuário</th>
            <th>Alvo</th>
            <th>Descrição</th>
            <th><a href="<?= $this->e($sortUrls['ip_address'] ?? '/audit-logs') ?>">IP<?= $this->e($sortIndicators['ip_address'] ?? '') ?></a></th>
            <th><a href="<?= $this->e($sortUrls['created_at'] ?? '/audit-logs') ?>">Data<?= $this->e($sortIndicators['created_at'] ?? '') ?></a></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
            <tr>
                <td><?= $log->id() ?></td>
                <td>
                    <?php $this->insert('components/action-badge', ['badge' => $log->actionBadge()]) ?>
                    <div style="margin-top: 4px; font-size: 12px; color: #64748b;">
                        <?= $this->e($log->action()) ?>
                    </div>
                </td>
                <td>
                    <?= $this->e($log->userName()) ?><br>
                    <small><?= $this->e($log->userEmail()) ?></small>
                </td>
                <td><?= $this->e($log->targetLabel()) ?></td>
                <td><?= $this->e($log->description()) ?></td>
                <td><?= $this->e($log->ipAddress()) ?></td>
                <td><?= $this->e($log->createdAt()) ?></td>
            </tr>
        <?php endforeach; ?>

        <?php if (count($logs) === 0): ?>
            <tr>
                <td colspan="7">Nenhum log encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->insert('components/pagination', ['pagination' => $pagination ?? []]) ?>

<?php $this->insert('layouts/footer') ?>
