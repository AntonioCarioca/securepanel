<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\ApiResponse;
use App\Middleware\ApiAdminMiddleware;
use App\Middleware\ApiAuthMiddleware;
use App\Models\User;
use App\Services\AuditLogService;
use Respect\Validation\Validator as v;

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
            ->map(fn($user) => $this->transformUser($user))
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
            $this->transformUser($user)
        );
    }

    public function storeUser(array $params = []): void
    {
        ApiAdminMiddleware::handle();

        $data = $this->requestData();

        $payload = [
            'name' => trim((string) ($data['name'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
            'password' => (string) ($data['password'] ?? ''),
            'role' => trim((string) ($data['role'] ?? 'user')),
        ];

        $validator = v::key('name', v::stringType()->length(3, 100))
            ->key('email', v::email()->notEmpty())
            ->key('password', v::stringType()->length(6, null))
            ->key('role', v::in(['admin', 'user']));

        if (!$validator->validate($payload)) {
            ApiResponse::error('Dados inválidos para criação do usuário.', 422, $payload);
        }

        if (User::where('email', $payload['email'])->exists()) {
            ApiResponse::error('Este e-mail já está cadastrado.', 422);
        }

        $user = User::create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => password_hash($payload['password'], PASSWORD_DEFAULT),
            'role' => $payload['role'],
        ]);

        $currentUser = auth();
        AuditLogService::log(
            'api.user.created',
            (int) ($currentUser['id'] ?? 0),
            'user',
            (int) $user->id,
            'Usuário criado via API: ' . $user->email
        );

        ApiResponse::success(
            'Usuário criado com sucesso.',
            $this->transformUser($user),
            [],
            201
        );
    }

    public function updateUser(array $params = []): void
    {
        ApiAdminMiddleware::handle();

        $id = (int) ($params['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            ApiResponse::error('Usuário não encontrado.', 404);
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'PUT';
        $data = $this->requestData();

        if ($method === 'PATCH') {
            $payload = [
                'name' => array_key_exists('name', $data) ? trim((string) $data['name']) : $user->name,
                'email' => array_key_exists('email', $data) ? trim((string) $data['email']) : $user->email,
                'role' => array_key_exists('role', $data) ? trim((string) $data['role']) : $user->role,
                'password' => array_key_exists('password', $data) ? (string) $data['password'] : '',
            ];
        } else {
            $payload = [
                'name' => trim((string) ($data['name'] ?? '')),
                'email' => trim((string) ($data['email'] ?? '')),
                'role' => trim((string) ($data['role'] ?? '')),
                'password' => (string) ($data['password'] ?? ''),
            ];
        }

        $validator = v::key('name', v::stringType()->length(3, 100))
            ->key('email', v::email()->notEmpty())
            ->key('role', v::in(['admin', 'user']));

        if (!$validator->validate($payload)) {
            ApiResponse::error('Dados inválidos para atualização do usuário.', 422, $payload);
        }

        $emailExists = User::where('email', $payload['email'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            ApiResponse::error('Este e-mail já está em uso.', 422);
        }

        $updateData = [
            'name' => $payload['name'],
            'email' => $payload['email'],
            'role' => $payload['role'],
        ];

        if (array_key_exists('password', $data) && trim((string) $payload['password']) !== '') {
            if (strlen($payload['password']) < 6) {
                ApiResponse::error('A senha deve ter pelo menos 6 caracteres.', 422);
            }

            $updateData['password'] = password_hash($payload['password'], PASSWORD_DEFAULT);
        }

        $user->update($updateData);
        $user->refresh();

        $currentUser = auth();
        AuditLogService::log(
            'api.user.updated',
            (int) ($currentUser['id'] ?? 0),
            'user',
            (int) $user->id,
            'Usuário atualizado via API: ' . $user->email
        );

        ApiResponse::success(
            'Usuário atualizado com sucesso.',
            $this->transformUser($user)
        );
    }

    public function deleteUser(array $params = []): void
    {
        ApiAdminMiddleware::handle();

        $id = (int) ($params['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            ApiResponse::error('Usuário não encontrado.', 404);
        }

        $currentUser = auth();

        if ((int) ($currentUser['id'] ?? 0) === (int) $user->id) {
            ApiResponse::error('Você não pode excluir seu próprio usuário.', 422);
        }

        $deletedUserId = (int) $user->id;
        $deletedUserEmail = (string) $user->email;

        $user->delete();

        AuditLogService::log(
            'api.user.deleted',
            (int) ($currentUser['id'] ?? 0),
            'user',
            $deletedUserId,
            'Usuário excluído via API: ' . $deletedUserEmail
        );

        ApiResponse::success(
            'Usuário excluído com sucesso.',
            [
                'id' => $deletedUserId,
                'email' => $deletedUserEmail,
            ]
        );
    }

    private function requestData(): array
    {
        if (!empty($GLOBALS['json_input']) && is_array($GLOBALS['json_input'])) {
            return $GLOBALS['json_input'];
        }

        return $_POST;
    }

    private function transformUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
