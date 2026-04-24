<?php
/**
 * View da tela de cadastro. O controller valida os dados e cria o usuário; esta view só exibe o formulário.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => 'Cadastro', 'authUser' => $authUser ?? null]) ?>

<section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
    <div class="mb-8 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-bold text-white shadow-lg shadow-blue-600/30">S</div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Criar conta</h1>
        <p class="mt-2 text-sm text-slate-500">Preencha os dados para começar a usar o SecurePanel.</p>
    </div>

    <form action="/register" method="POST" class="space-y-5">
        <?php $this->insert('components/csrf') ?>

        <div>
            <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nome</label>
            <input id="name" type="text" name="name" value="<?= $this->e(old_value('name')) ?>" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">E-mail</label>
            <input id="email" type="email" name="email" value="<?= $this->e(old_value('email')) ?>" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Senha</label>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Confirmar senha</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
            Cadastrar
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-500">
        Já tem conta? <a href="/login" class="font-semibold text-blue-600 hover:text-blue-700">Voltar para login</a>
    </p>
</section>

<?php $this->insert('layouts/footer', ['authUser' => $authUser ?? null]) ?>
