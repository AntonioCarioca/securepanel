<?php

declare(strict_types=1);

/**
 * Seeder PHP que cria ou atualiza o usuário administrador padrão.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace Database\Seeders;

use App\Models\User;

/**
 * Seeder PHP que cria ou atualiza o usuário administrador padrão.
 */
class AdminUserSeeder
{
    /**
     * Executa este seeder.
     */
    public function run(): void
    {
        $email = 'admin@auth-system.dev.localhost';

        $user = User::where('email', $email)->first();

        if ($user) {
            echo "Admin já existe. Atualizando...\n";

            $user->update([
                'name' => 'Administrador',
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'role' => 'admin',
            ]);

            return;
        }

        User::create([
            'name' => 'Administrador',
            'email' => $email,
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'admin',
        ]);

        echo "Admin criado com sucesso.\n";
    }
}
