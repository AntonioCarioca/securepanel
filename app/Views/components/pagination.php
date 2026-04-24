<?php
/**
 * Componente reutilizável de paginação. Recebe as páginas já calculadas e só renderiza os links.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php if (($pagination['totalPages'] ?? 1) > 1): ?>
    <nav class="mt-6 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
        <div class="text-sm text-slate-500">
            Página <span class="font-semibold text-slate-700"><?= (int) ($pagination['currentPage'] ?? 1) ?></span>
            de <span class="font-semibold text-slate-700"><?= (int) ($pagination['totalPages'] ?? 1) ?></span>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <?php if (!empty($pagination['previousUrl'])): ?>
                <a href="<?= $this->e($pagination['previousUrl']) ?>" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Anterior</a>
            <?php endif; ?>

            <?php foreach ($pagination['items'] as $item): ?>
                <?php if ($item === '...'): ?>
                    <span class="px-2 text-sm text-slate-400">...</span>
                <?php elseif ($item === $pagination['currentPage']): ?>
                    <span class="rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm"><?= $item ?></span>
                <?php else: ?>
                    <a href="<?= $this->e($pagination['url']((int) $item)) ?>" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50"><?= $item ?></a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (!empty($pagination['nextUrl'])): ?>
                <a href="<?= $this->e($pagination['nextUrl']) ?>" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Próxima</a>
            <?php endif; ?>
        </div>
    </nav>
<?php endif; ?>
