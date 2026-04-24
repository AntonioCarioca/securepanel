<?php
/**
 * View da listagem de usuários. Filtros, URLs e presenters são preparados no UserController.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
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
            <option value="user" <?= selected_attr($role ?? '', 'user') ?>>Usuário</option>
            <option value="admin" <?= selected_attr($role ?? '', 'admin') ?>>Admin</option>
        </select>
    </div>

    <input type="hidden" name="sort" value="<?= $this->e((string) ($sort ?? 'created_at')) ?>">
    <input type="hidden" name="direction" value="<?= $this->e((string) ($direction ?? 'desc')) ?>">

    <button type="submit">Aplicar</button>
    <a href="/users" style="margin-left: 10px;">Limpar filtros</a>
</form>

<p style="margin-bottom: 16px;">
    <a href="<?= $this->e($exportUrl ?? '/users/export') ?>">Exportar CSV</a>
</p>

<?php $this->insert('components/active-filters', ['filters' => $activeFilters ?? [], 'clearUrl' => '/users']) ?>

<p>Total de registros: <?= (int) ($total ?? 0) ?></p>

<table border="1" cellpadding="8" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>ID</th>
            <th><a href="<?= $this->e($sortUrls['name'] ?? '/users') ?>">Nome<?= $this->e($sortIndicators['name'] ?? '') ?></a></th>
            <th><a href="<?= $this->e($sortUrls['email'] ?? '/users') ?>">E-mail<?= $this->e($sortIndicators['email'] ?? '') ?></a></th>
            <th>Perfil</th>
            <th><a href="<?= $this->e($sortUrls['created_at'] ?? '/users') ?>">Data<?= $this->e($sortIndicators['created_at'] ?? '') ?></a></th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user->id() ?></td>
                <td><?= $this->e($user->name()) ?></td>
                <td><?= $this->e($user->email()) ?></td>
                <td><?php $this->insert('components/role-badge', ['badge' => $user->roleBadge()]) ?></td>
                <td><?= $this->e($user->createdAt()) ?></td>
                <td>
                    <a href="<?= $this->e($user->editUrl()) ?>">Editar</a>

                    <form action="<?= $this->e($user->deleteUrl()) ?>" method="POST" style="display:inline;">
                        <?php $this->insert('components/csrf') ?>
                        <button type="submit" onclick="return confirm('Deseja excluir este usuário?')">
                            Excluir
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (count($users) === 0): ?>
            <tr>
                <td colspan="6">Nenhum usuário encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->insert('components/pagination', ['pagination' => $pagination ?? []]) ?>

<?php $this->insert('layouts/footer') ?>
