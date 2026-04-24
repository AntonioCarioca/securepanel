<?php
/**
 * Navbar exibida para usuário autenticado, com links conforme perfil.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<nav style="margin-bottom: 20px;">
    <?php foreach (auth_nav_links($authUser ?? null) as $index => $link): ?>
        <?= $index > 0 ? ' | ' : '' ?><a href="<?= $this->e($link['url']) ?>"><?= $this->e($link['label']) ?></a>
    <?php endforeach; ?>

    | <form action="/logout" method="POST" style="display:inline;">
        <?php $this->insert('components/csrf') ?>
        <button type="submit">Sair</button>
      </form>
</nav>
