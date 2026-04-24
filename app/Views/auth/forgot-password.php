<?php
/**
 * View para solicitar o link de redefinição de senha por e-mail.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => 'Esqueci minha senha', 'authUser' => $authUser ?? null]) ?>

<section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Recuperar senha</h1>
        <p class="mt-2 text-sm text-slate-500">Informe seu e-mail para receber o link de redefinição.</p>
    </div>

    <form action="/forgot-password" method="POST" class="space-y-5">
        <?php $this->insert('components/csrf') ?>

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">E-mail</label>
            <input id="email" type="email" name="email" value="<?= $this->e(old_value('email')) ?>" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
            Enviar link de recuperação
        </button>
    </form>

    <p class="mt-6 text-center text-sm">
        <a href="/login" class="font-semibold text-blue-600 hover:text-blue-700">Voltar para login</a>
    </p>
</section>

<?php $this->insert('layouts/footer', ['authUser' => $authUser ?? null]) ?>
