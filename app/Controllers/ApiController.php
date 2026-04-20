<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ApiResponse;
use App\Middleware\ApiAdminMiddleware;
use App\Middleware\ApiAuthMiddleware;
use App\Models\User;

class ApiController
{
    public function me(array $params = []): void
    {
        ApiAuthMiddleware::handle();

        $user = auth();

        ApiResponse::success(
            'Usuário autenticado recuperado com sucesso.',
            [
                'id' => $user['id'] ?? null,
                'name' => $user['name'] ?? null,
                'email' => $user['email'] ?? null,
                'role' => $user['role'] ?? null,
            ]
        );
    }

    public function users(array $params = []): void
    {
        ApiAdminMiddleware::handle();

        $users = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at', 'updated_at'])
            ->orderBy('id', 'desc')
            ->get()
            ->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ])
            ->values()
            ->all();

        ApiResponse::success(
            'Usuários listados com sucesso.',
            $users,
            [
                'total' => count($users),
            ]
        );
    }

    public function showUser(array $params = []): void
    {
        ApiAdminMiddleware::handle();

        $id = (int) ($params['id'] ?? 0);

        $user = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at', 'updated_at'])
            ->find($id);

        if (!$user) {
            ApiResponse::error('Usuário não encontrado.', 404);
        }

        ApiResponse::success(
            'Usuário recuperado com sucesso.',
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        );
    }
}
