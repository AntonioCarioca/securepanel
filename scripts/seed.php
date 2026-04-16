<?php

declare(strict_types=1);

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