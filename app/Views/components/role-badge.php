<?php
/**
 * Componente que renderiza o badge visual do perfil do usuário.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide shadow-sm"
      style="background: <?= $this->e($badge['background']) ?>; color: <?= $this->e($badge['color']) ?>;">
    <?= $this->e($badge['label']) ?>
</span>
