<?php

declare(strict_types=1);

/**
 * Script CLI para executar os arquivos SQL de migrations.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

require_once __DIR__ . '/../bootstrap/app.php';

try {
    $driver = $_ENV['DB_CONNECTION'] ?? 'mysql';
    $host = $_ENV['DB_HOST'] ?? 'mysql';
    $port = $_ENV['DB_PORT'] ?? '3306';
    $database = $_ENV['DB_DATABASE'] ?? '';
    $username = $_ENV['DB_USERNAME'] ?? '';
    $password = $_ENV['DB_PASSWORD'] ?? '';

    if ($database === '') {
        throw new RuntimeException('DB_DATABASE não está definido no .env');
    }

    $dsn = sprintf(
        '%s:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $driver,
        $host,
        $port,
        $database
    );

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    $files = glob(__DIR__ . '/../database/migrations/*.sql');

    if ($files === false || empty($files)) {
        throw new RuntimeException('Nenhum arquivo de migration foi encontrado.');
    }

    sort($files);

    echo "Iniciando migrations...\n\n";

    foreach ($files as $file) {
        $sql = file_get_contents($file);

        if ($sql === false) {
            throw new RuntimeException('Não foi possível ler o arquivo: ' . $file);
        }

        $filename = basename($file);

        echo "Executando: {$filename} ... ";

        $pdo->exec($sql);

        echo "OK\n";
    }

    echo "\nMigrations executadas com sucesso.\n";
} catch (Throwable $e) {
    fwrite(STDERR, "\nErro ao executar migrations:\n");
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
