<?php
/**
 * Componente que mostra filtros ativos como badges visuais.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php if (!empty($filters)): ?>
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold text-slate-700">Filtros ativos</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    <?php foreach ($filters as $filter): ?>
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-sm font-medium text-slate-700">
                            <?= $this->e($filter) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if (!empty($clearUrl)): ?>
                <a href="<?= $this->e($clearUrl) ?>" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Limpar todos
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
