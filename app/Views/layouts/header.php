<?php
/**
 * Cabeçalho compartilhado com abertura do HTML, título, navbar e mensagens.
 *
 * Comentado para estudo: a view deve conter o mínimo possível de lógica.
 * Regras de negócio ficam nos controllers/services e transformações ficam nos presenters/helpers.
 *
 * Este layout usa Tailwind CSS compilado localmente em /assets/css/app.css.
 * Quando existe usuário autenticado, renderiza uma estrutura de painel admin com sidebar.
 * Quando não existe usuário autenticado, renderiza uma área centralizada para telas públicas como 
 * login/cadastro.
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->e($title ?? 'SecurePanel') ?></title>
    <link href="/assets/css/app.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <?php if (!empty($authUser)): ?>
        <div class="flex min-h-screen">
            <?php $this->insert('partials/navbar', ['authUser' => $authUser]) ?>

            <div class="flex min-w-0 flex-1 flex-col">
                <header class="border-b border-slate-200 bg-white/90 px-6 py-4 shadow-sm backdrop-blur">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-500">Painel administrativo</p>
                            <h1 class="text-xl font-bold text-slate-900"><?= $this->e($title ?? 'SecurePanel') ?></h1>
                        </div>

                        <div class="hidden rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600 md:block">
                            <?= $this->e($authUser['name'] ?? 'Usuário') ?>
                        </div>
                    </div>
                </header>

                <main class="flex-1 p-6 lg:p-8">
                    <?php $this->insert('partials/alerts') ?>
    <?php else: ?>
        <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <?php $this->insert('partials/alerts') ?>
    <?php endif; ?>
