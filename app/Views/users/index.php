<?php $this->insert('layouts/header', ['title' => 'Usuários', 'authUser' => $authUser ?? null]) ?>

<h1>Usuários</h1>

<p>
    <a href="/dashboard">Voltar ao dashboard</a> |
    <a href="/users/create">Novo usuário</a>
</p>

<form action="/users" method="GET" style="margin-bottom: 20px;">
    <div style="margin-bottom: 12px;">
        <label for="search">Buscar por nome ou e-mail</label><br>
        <input
            id="search"
            type="text"
            name="search"
            value="<?= $this->e((string) ($search ?? '')) ?>"
            placeholder="Digite o nome ou e-mail"
        >
    </div>

    <div style="margin-bottom: 12px;">
        <label for="role">Perfil</label><br>
        <select id="role" name="role">
            <option value="">Todos</option>
            <option value="user" <?= ($role ?? '') === 'user' ? 'selected' : '' ?>>Usuário</option>
            <option value="admin" <?= ($role ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </div>

    <div style="margin-bottom: 12px;">
        <label for="sort">Ordenar por</label><br>
        <select id="sort" name="sort">
            <option value="created_at" <?= ($sort ?? '') === 'created_at' ? 'selected' : '' ?>>Data</option>
            <option value="name" <?= ($sort ?? '') === 'name' ? 'selected' : '' ?>>Nome</option>
            <option value="email" <?= ($sort ?? '') === 'email' ? 'selected' : '' ?>>E-mail</option>
        </select>
    </div>

    <div style="margin-bottom: 12px;">
        <label for="direction">Direção</label><br>
        <select id="direction" name="direction">
            <option value="asc" <?= ($direction ?? '') === 'asc' ? 'selected' : '' ?>>Crescente</option>
            <option value="desc" <?= ($direction ?? '') === 'desc' ? 'selected' : '' ?>>Decrescente</option>
        </select>
    </div>

    <button type="submit">Aplicar</button>

    <a href="/users" style="margin-left: 10px;">Limpar filtros</a>
</form>

<p>
    Total de registros: <?= (int) ($total ?? 0) ?>
</p>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Perfil</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($users) > 0): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= (int) $user->id ?></td>
                    <td><?= $this->e($user->name) ?></td>
                    <td><?= $this->e($user->email) ?></td>
                    <td><?= $this->e($user->role) ?></td>
                    <td>
                        <a href="/users/<?= (int) $user->id ?>/edit">Editar</a>

                        <form action="/users/<?= (int) $user->id ?>/delete" method="POST" style="display:inline;">
                            <input type="hidden" name="_csrf" value="<?= $this->e(csrf_token()) ?>">
                            <button type="submit" onclick="return confirm('Deseja excluir este usuário?')">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Nenhum usuário encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if (($totalPages ?? 1) > 1): ?>
    <nav style="margin-top: 20px;">
        <?php
            $currentPage = (int) ($page ?? 1);
            $pages = (int) ($totalPages ?? 1);

            $buildPageUrl = function (int $targetPage) use ($search, $role, $sort, $direction): string {
                $query = ['page' => $targetPage];

                if (!empty($search)) {
                    $query['search'] = $search;
                }

                if (!empty($role)) {
                    $query['role'] = $role;
                }

                if (!empty($sort)) {
                    $query['sort'] = $sort;
                }

                if (!empty($direction)) {
                    $query['direction'] = $direction;
                }

                return '/users?' . http_build_query($query);
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
