<?php
/**
 * View para cadastrar uma nova senha a partir de um token já validado pelo controller.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => 'Redefinir senha', 'authUser' => $authUser ?? null]) ?>

<section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Redefinir senha</h1>
        <p class="mt-2 text-sm text-slate-500">Crie uma nova senha segura para sua conta.</p>
    </div>

    <form action="/reset-password" method="POST" class="space-y-5">
        <?php $this->insert('components/csrf') ?>
        <input type="hidden" name="token" value="<?= $this->e((string) ($token ?? '')) ?>">

        <div>
            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Nova senha</label>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Confirmar nova senha</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
            Salvar nova senha
        </button>
    </form>

    <p class="mt-6 text-center text-sm">
        <a href="/login" class="font-semibold text-blue-600 hover:text-blue-700">Voltar para login</a>
    </p>
</section>

<?php $this->insert('layouts/footer', ['authUser' => $authUser ?? null]) ?>
