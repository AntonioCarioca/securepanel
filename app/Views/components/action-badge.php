<?php
/**
 * Componente que renderiza o badge visual da ação do audit log.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<span style="
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    background: <?= $this->e($badge['background']) ?>;
    color: <?= $this->e($badge['color']) ?>;
    white-space: nowrap;
">
    <?= $this->e($badge['label']) ?>
</span>
