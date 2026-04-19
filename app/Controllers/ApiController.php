<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\ApiAdminMiddleware;
use App\Middleware\ApiAuthMiddleware;
use App\Models\User;

class ApiController
{
    public function me(array $params = []): void
    {
        ApiAuthMiddleware::handle();

        $user = auth();

        $this->json([
            'success' => true,
            'data' => [
                'id' => $user['id'] ?? null,
                'name' => $user['name'] ?? null,
                'email' => $user['email'] ?? null,
                'role' => $user['role'] ?? null,
            ],
        ]);
    }

    public function users(array $params = []): void
    {
        ApiAdminMiddleware::handle();

        $users = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at', 'updated_at'])
            ->orderBy('id', 'desc')
            ->get();

        $this->json([
            'success' => true,
            'data' => $users->map(fn($user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]),
        ]);
    }

    private function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        );

        exit;
    }
}
