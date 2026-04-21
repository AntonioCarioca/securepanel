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

        $search = trim($_GET['search'] ?? '');
        $role = trim($_GET['role'] ?? '');
        $sort = trim($_GET['sort'] ?? 'created_at');
        $direction = trim($_GET['direction'] ?? 'desc');
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = (int) ($_GET['per_page'] ?? 10);

        $allowedSorts = ['name', 'email', 'created_at'];
        $allowedDirections = ['asc', 'desc'];
        $allowedRoles = ['admin', 'user'];
        $allowedPerPage = [5, 10, 15, 20, 50];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, $allowedDirections, true)) {
            $direction = 'desc';
        }

        if ($role !== '' && !in_array($role, $allowedRoles, true)) {
            $role = '';
        }

        if ($page < 1) {
            $page = 1;
        }

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        $query = User::query()
            ->select(['id', 'name', 'email', 'role', 'created_at', 'updated_at']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        $total = (clone $query)->count();
        $totalPages = (int) ceil($total / $perPage);

        if ($totalPages < 1) {
            $totalPages = 1;
        }

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $users = $query
            ->orderBy($sort, $direction)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
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
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages,
                ],
                'filters' => [
                    'search' => $search,
                    'role' => $role,
                    'sort' => $sort,
                    'direction' => $direction,
                ],
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
