<?php
/**
 * Formulário reutilizável para criação e edição de usuários.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<form action="<?= $this->e($form['action']) ?>" method="POST" class="space-y-5">
    <?php $this->insert('components/csrf') ?>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nome</label>
            <input id="name" type="text" name="name" value="<?= $this->e($form['name']) ?>" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">E-mail</label>
            <input id="email" type="email" name="email" value="<?= $this->e($form['email']) ?>" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700"><?= $this->e($form['passwordLabel']) ?></label>
            <input id="password" type="password" name="password" <?= !empty($form['passwordRequired']) ? 'required' : '' ?>
                   class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
            <?php if (empty($form['passwordRequired'])): ?>
                <p class="mt-2 text-xs text-slate-500">Deixe em branco para manter a senha atual.</p>
            <?php endif; ?>
        </div>

        <div>
            <label for="role" class="mb-2 block text-sm font-semibold text-slate-700">Perfil</label>
            <select id="role" name="role" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                <option value="user" <?= selected_attr($form['role'], 'user') ?>>Usuário</option>
                <option value="admin" <?= selected_attr($form['role'], 'admin') ?>>Admin</option>
            </select>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 pt-2">
        <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
            <?= $this->e($form['button']) ?>
        </button>
        <a href="/users" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Cancelar</a>
    </div>
</form>
