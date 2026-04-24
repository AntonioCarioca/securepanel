<?php
/**
 * Componente reutilizável de paginação. Recebe as páginas já calculadas e só renderiza os links.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php if (($pagination['totalPages'] ?? 1) > 1): ?>
    <nav style="margin-top: 20px;">
        <?php if (!empty($pagination['previousUrl'])): ?>
            <a href="<?= $this->e($pagination['previousUrl']) ?>">Anterior</a>
        <?php endif; ?>

        <?php foreach ($pagination['items'] as $item): ?>
            <?php if ($item === '...'): ?>
                <span style="margin: 0 6px;">...</span>
            <?php elseif ($item === $pagination['currentPage']): ?>
                <strong style="margin: 0 6px;"><?= $item ?></strong>
            <?php else: ?>
                <a href="<?= $this->e($pagination['url']((int) $item)) ?>" style="margin: 0 6px;"><?= $item ?></a>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (!empty($pagination['nextUrl'])): ?>
            <a href="<?= $this->e($pagination['nextUrl']) ?>">Próxima</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>
