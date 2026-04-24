<?php
/**
 * Partial que exibe mensagens flash de erro e sucesso.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php if ($message = $this->flash('error')): ?>
    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 shadow-sm">
        <?= $this->e($message) ?>
    </div>
<?php endif; ?>

<?php if ($message = $this->flash('success')): ?>
    <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm">
        <?= $this->e($message) ?>
    </div>
<?php endif; ?>
