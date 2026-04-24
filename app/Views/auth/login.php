<?php
/**
 * View da tela de login. A validação e criação de sessão ficam no AuthController; aqui ficam apenas os campos e links.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 */
?>
<?php $this->insert('layouts/header', ['title' => 'Login', 'authUser' => $authUser ?? null]) ?>

<section class="rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-slate-200/60">
    <div class="mb-8 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-bold text-white shadow-lg shadow-blue-600/30">SP</div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Entrar no SecurePanel</h1>
        <p class="mt-2 text-sm text-slate-500">Acesse sua conta para gerenciar o sistema.</p>
    </div>

    <form action="/login" method="POST" class="space-y-5">
        <?php $this->insert('components/csrf') ?>

        <div>
            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">E-mail</label>
            <input id="email" type="email" name="email" value="<?= $this->e(old_value('email')) ?>" required
                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <div>
            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Senha</label>
            <input id="password" type="password" name="password" required
                   class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
        </div>

        <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">
            Entrar
        </button>
    </form>

    <div class="mt-6 flex flex-col gap-2 text-center text-sm">
        <a href="/forgot-password" class="font-medium text-blue-600 hover:text-blue-700">Esqueci minha senha</a>
        <span class="text-slate-500">Ainda não tem conta? <a href="/register" class="font-semibold text-blue-600 hover:text-blue-700">Criar conta</a></span>
    </div>
</section>

<?php $this->insert('layouts/footer', ['authUser' => $authUser ?? null]) ?>
