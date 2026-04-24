<?php
/**
 * Navbar exibida para usuário autenticado, com links conforme perfil.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<aside class="hidden w-72 shrink-0 border-r border-slate-800 bg-slate-950 text-white lg:block">
    <div class="flex h-full flex-col">
        <div class="border-b border-slate-800 px-6 py-6">
            <a href="/dashboard" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-lg font-bold shadow-lg shadow-blue-600/30">SP</span>
                <div>
                    <p class="text-lg font-bold tracking-tight">SecurePanel</p>
                    <p class="text-xs text-slate-400">Admin dashboard</p>
                </div>
            </a>
        </div>

        <nav class="flex-1 space-y-1 px-4 py-5">
            <?php foreach (auth_nav_links($authUser ?? null) as $link): ?>
                <a href="<?= $this->e($link['url']) ?>" class="flex items-center rounded-xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-slate-900 hover:text-white">
                    <?= $this->e($link['label']) ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="border-t border-slate-800 p-4">
            <div class="mb-4 rounded-xl bg-slate-900 p-4">
                <p class="text-sm font-semibold text-white"><?= $this->e($authUser['name'] ?? 'Usuário') ?></p>
                <p class="mt-1 truncate text-xs text-slate-400"><?= $this->e($authUser['email'] ?? '') ?></p>
                <p class="mt-2 inline-flex rounded-full bg-blue-500/10 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-blue-300">
                    <?= $this->e($authUser['role'] ?? 'user') ?>
                </p>
            </div>

            <form action="/logout" method="POST">
                <?php $this->insert('components/csrf') ?>
                <button type="submit" class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-slate-950">
                    Sair
                </button>
            </form>
        </div>
    </div>
</aside>

<div class="border-b border-slate-800 bg-slate-950 px-4 py-4 text-white lg:hidden">
    <div class="flex items-center justify-between gap-4">
        <a href="/dashboard" class="text-lg font-bold">SecurePanel</a>
        <form action="/logout" method="POST">
            <?php $this->insert('components/csrf') ?>
            <button type="submit" class="rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white">Sair</button>
        </form>
    </div>

    <nav class="mt-4 flex gap-2 overflow-x-auto pb-1">
        <?php foreach (auth_nav_links($authUser ?? null) as $link): ?>
            <a href="<?= $this->e($link['url']) ?>" class="whitespace-nowrap rounded-lg bg-slate-900 px-3 py-2 text-sm text-slate-200">
                <?= $this->e($link['label']) ?>
            </a>
        <?php endforeach; ?>
    </nav>
</div>
