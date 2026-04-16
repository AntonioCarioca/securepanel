<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;

class AdminUserSeeder
{
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
