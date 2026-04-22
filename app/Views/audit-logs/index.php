<?php $this->insert('layouts/header', ['title' => 'Audit Logs', 'authUser' => $authUser ?? null]) ?>

<h1>Audit Logs</h1>

<p>
    <a href="/dashboard">Voltar ao dashboard</a>
</p>

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
                <option value="<?= $this->e($item) ?>" <?= ($action ?? '') === $item ? 'selected' : '' ?>>
                    <?= $this->e($item) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div style="margin-bottom: 12px;">
        <label for="date_from">Data inicial</label><br>
        <input
            id="date_from"
            type="date"
            name="date_from"
            value="<?= $this->e((string) ($dateFrom ?? '')) ?>"
        >
    </div>

    <div style="margin-bottom: 12px;">
        <label for="date_to">Data final</label><br>
        <input
            id="date_to"
            type="date"
            name="date_to"
            value="<?= $this->e((string) ($dateTo ?? '')) ?>"
        >
    </div>

    <button type="submit">Aplicar</button>
    <a href="/audit-logs" style="margin-left: 10px;">Limpar filtros</a>
</form>

<?php
    $activeFilters = [];

    if (!empty($search)) {
        $activeFilters[] = 'Busca: "' . $this->e($search) . '"';
    }

    if (!empty($action)) {
        $activeFilters[] = 'Ação: ' . $this->e($action);
    }

    if (!empty($dateFrom)) {
        $activeFilters[] = 'De: ' . $this->e(date('d/m/Y', strtotime($dateFrom)));
    }

    if (!empty($dateTo)) {
        $activeFilters[] = 'Até: ' . $this->e(date('d/m/Y', strtotime($dateTo)));
    }
?>

<?php if (!empty($activeFilters)): ?>
    <div style="margin-bottom: 16px;">
        <strong>Filtros ativos:</strong>

        <div style="margin-top: 8px;">
            <?php foreach ($activeFilters as $filter): ?>
                <span style="
                    display: inline-block;
                    background: #f1f5f9;
                    border: 1px solid #cbd5e1;
                    border-radius: 999px;
                    padding: 6px 10px;
                    margin: 4px 6px 4px 0;
                    font-size: 14px;
                ">
                    <?= $filter ?>
                </span>
            <?php endforeach; ?>

            <a href="/audit-logs" style="margin-left: 8px;">Limpar todos</a>
        </div>
    </div>
<?php endif; ?>

<p>Total de registros: <?= (int) ($total ?? 0) ?></p>

<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Ação</th>
            <th>Usuário</th>
            <th>Alvo</th>
            <th>Descrição</th>
            <th>IP</th>
            <th>Data</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($logs) > 0): ?>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= (int) $log->id ?></td>
                    <td><?= $this->e($log->action) ?></td>
                    <td>
                        <?php if ($log->user): ?>
                            <?= $this->e($log->user->name) ?><br>
                            <small><?= $this->e($log->user->email) ?></small>
                        <?php else: ?>
                            <em>Usuário removido / não encontrado</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $this->e((string) ($log->target_type ?? '-')) ?>
                        <?php if (!empty($log->target_id)): ?>
                            #<?= (int) $log->target_id ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $this->e((string) ($log->description ?? '-')) ?></td>
                    <td><?= $this->e((string) ($log->ip_address ?? '-')) ?></td>
                    <td><?= $this->e(date('d/m/Y H:i', strtotime((string) $log->created_at))) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Nenhum log encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if (($totalPages ?? 1) > 1): ?>
    <nav style="margin-top: 20px;">
        <?php
            $currentPage = (int) ($page ?? 1);
            $pages = (int) ($totalPages ?? 1);

            $buildPageUrl = function (int $targetPage) use ($search, $action, $dateFrom, $dateTo): string {
                $query = ['page' => $targetPage];

                if (!empty($search)) {
                    $query['search'] = $search;
                }

                if (!empty($action)) {
                    $query['action'] = $action;
                }

                if (!empty($dateFrom)) {
                    $query['date_from'] = $dateFrom;
                }

                if (!empty($dateTo)) {
                    $query['date_to'] = $dateTo;
                }

                return '/audit-logs?' . http_build_query($query);
            };
            
            $visiblePages = [];

            if ($pages <= 7) {
                for ($i = 1; $i <= $pages; $i++) {
                    $visiblePages[] = $i;
                }
            } else {
                $visiblePages[] = 1;

                if ($currentPage <= 4) {
                    for ($i = 2; $i <= 5; $i++) {
                        $visiblePages[] = $i;
                    }
                    $visiblePages[] = '...';
                    $visiblePages[] = $pages;
                } elseif ($currentPage >= $pages - 3) {
                    $visiblePages[] = '...';
                    for ($i = $pages - 4; $i < $pages; $i++) {
                        $visiblePages[] = $i;
                    }
                    $visiblePages[] = $pages;
                } else {
                    $visiblePages[] = '...';
                    for ($i = $currentPage - 1; $i <= $currentPage + 1; $i++) {
                        $visiblePages[] = $i;
                    }
                    $visiblePages[] = '...';
                    $visiblePages[] = $pages;
                }
            }
        ?>

        <?php if ($currentPage > 1): ?>
            <a href="<?= $this->e($buildPageUrl($currentPage - 1)) ?>">Anterior</a>
        <?php endif; ?>

        <?php foreach ($visiblePages as $item): ?>
            <?php if ($item === '...'): ?>
                <span style="margin: 0 6px;">...</span>
            <?php elseif ($item === $currentPage): ?>
                <strong style="margin: 0 6px;"><?= $item ?></strong>
            <?php else: ?>
                <a href="<?= $this->e($buildPageUrl((int) $item)) ?>" style="margin: 0 6px;"><?= $item ?></a>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($currentPage < $pages): ?>
            <a href="<?= $this->e($buildPageUrl($currentPage + 1)) ?>">Próxima</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>

<?php $this->insert('layouts/footer') ?>
