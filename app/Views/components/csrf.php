<?php
/**
 * Componente que imprime o input hidden de CSRF em formulários POST.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<input type="hidden" name="_csrf" value="<?= $this->e(csrf_token()) ?>">
