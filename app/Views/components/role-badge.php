<?php
/**
 * Componente que renderiza o badge visual do perfil do usuário.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<span style="
    background: <?= $this->e($badge['background']) ?>;
    color: <?= $this->e($badge['color']) ?>;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
">
    <?= $this->e($badge['label']) ?>
</span>
