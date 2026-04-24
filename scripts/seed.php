<?php

declare(strict_types=1);

/**
 * Script CLI para executar seeders PHP.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

require_once __DIR__ . '/../bootstrap/app.php';

use Database\Seeders\AdminUserSeeder;

try {
    echo "Iniciando seeders...\n\n";

    $seeders = [
        AdminUserSeeder::class,
    ];

    foreach ($seeders as $seederClass) {
        echo "Executando: {$seederClass}...\n";

        $seeder = new $seederClass();
        $seeder->run();

        echo "OK\n\n";
    }

    echo "Seeders executados com sucesso.\n";
} catch (Throwable $e) {
    fwrite(STDERR, "Erro ao executar seeders:\n");
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}