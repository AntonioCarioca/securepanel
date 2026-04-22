<nav style="margin-bottom: 20px;">
    <a href="/dashboard">Dashboard</a>

    <?php if (($authUser['role'] ?? 'user') === 'admin'): ?>
        | <a href="/users">Usuários</a>
        | <a href="/audit-logs">Audit Logs</a>
    <?php endif; ?>

    | <form action="/logout" method="POST" style="display:inline;">
        <input type="hidden" name="_csrf" value="<?= $this->e(csrf_token()) ?>">
        <button type="submit">Sair</button>
      </form>
</nav>