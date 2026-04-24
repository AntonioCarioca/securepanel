<?php

declare(strict_types=1);

/**
 * Controlador administrativo responsável pelo CRUD web de usuários, filtros, paginação e exportação CSV.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Controllers;

use App\Core\View;
use App\Middleware\AdminMiddleware;
use App\Models\User;
use App\Presenters\UserPresenter;
use App\Services\AuditLogService;
use Respect\Validation\Validator as v;

/**
 * Controlador administrativo responsável pelo CRUD web de usuários, filtros, paginação e exportação CSV.
 */
class UserController
{
    /**
     * Lista registros com filtros, paginação e dados preparados para a view.
     */
    public function index(array $params = []): void
    {
        AdminMiddleware::handle();

        $search = trim($_GET['search'] ?? '');
        $role = trim($_GET['role'] ?? '');
        $sort = trim($_GET['sort'] ?? 'created_at');
        $direction = trim($_GET['direction'] ?? 'desc');
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 10;

        if ($page < 1) {
            $page = 1;
        }

        $allowedSorts = ['name', 'email', 'created_at'];
        $allowedDirections = ['asc', 'desc'];
        $allowedRoles = ['admin', 'user'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, $allowedDirections, true)) {
            $direction = 'desc';
        }

        if ($role !== '' && !in_array($role, $allowedRoles, true)) {
            $role = '';
        }

        $query = User::query();

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
            ->map(fn(User $user) => new UserPresenter($user))
            ->all();

        $baseQuery = [
            'search' => $search,
            'role' => $role,
            'sort' => $sort,
            'direction' => $direction,
        ];

        $filterQuery = [
            'search' => $search,
            'role' => $role,
            'sort' => $sort,
            'direction' => $direction,
        ];

        View::render('users/index', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'sort' => $sort,
            'direction' => $direction,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages,
            'activeFilters' => user_active_filters($search, $role, $sort, $direction),
            'exportUrl' => build_url('/users/export', $baseQuery),
            'pagination' => pagination_view_data('/users', $page, $totalPages, $baseQuery),
            'sortUrls' => [
                'name' => sort_url('/users', 'name', $sort, $direction, $filterQuery),
                'email' => sort_url('/users', 'email', $sort, $direction, $filterQuery),
                'created_at' => sort_url('/users', 'created_at', $sort, $direction, $filterQuery),
            ],
            'sortIndicators' => [
                'name' => sort_indicator('name', $sort, $direction),
                'email' => sort_indicator('email', $sort, $direction),
                'created_at' => sort_indicator('created_at', $sort, $direction),
            ],
        ]);
    }

    /**
     * Exibe o formulário de criação.
     */
    public function showCreate(): void
    {
        AdminMiddleware::handle();

        View::render('users/create', [
            'form' => user_form_data(),
        ]);
    }

    /**
     * Valida e cria um novo registro.
     */
    public function store(): void
    {
        AdminMiddleware::handle();

        $_SESSION['_old'] = $_POST;

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'role' => $_POST['role'] ?? 'user',
        ];

        $validator = v::key('name', v::stringType()->length(3, 100))
            ->key('email', v::email()->notEmpty())
            ->key('password', v::stringType()->length(6, null))
            ->key('role', v::in(['admin', 'user']));

        if (!$validator->validate($data)) {
            flash('error', 'Verifique os dados do formulário.');
            back();
        }

        if (User::where('email', $data['email'])->exists()) {
            flash('error', 'Este e-mail já está cadastrado.');
            back();
        }

        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'],
        ]);

        $currentUser = auth();

        AuditLogService::log(
            'user.created',
            (int) ($currentUser['id'] ?? 0),
            'user',
            (int) $newUser->id,
            'Usuário criou um novo usuário: ' . $newUser->email
        );

        unset($_SESSION['_old']);

        flash('success', 'Usuário criado com sucesso.');
        redirect('/users');
    }

    /**
     * Busca o registro pelo ID e exibe o formulário de edição.
     */
    public function showEdit(array $params): void
    {
        AdminMiddleware::handle();

        $id = (int) ($params['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            flash('error', 'Usuário não encontrado.');
            redirect('/users');
        }

        View::render('users/edit', [
            'form' => user_form_data($user),
        ]);
    }

    /**
     * Valida e atualiza o registro informado.
     */
    public function update(array $params): void
    {
        AdminMiddleware::handle();

        $_SESSION['_old'] = $_POST;

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $id = (int) ($params['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            flash('error', 'Usuário não encontrado.');
            redirect('/users');
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role' => $_POST['role'] ?? 'user',
        ];

        $validator = v::key('name', v::stringType()->length(3, 100))
            ->key('email', v::email()->notEmpty())
            ->key('role', v::in(['admin', 'user']));

        if (!$validator->validate($data)) {
            flash('error', 'Verifique os dados do formulário.');
            back();
        }

        $emailExists = User::where('email', $data['email'])
            ->where('id', '!=', $user->id)
            ->exists();

        if ($emailExists) {
            flash('error', 'Este e-mail já está em uso.');
            back();
        }

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ];

        $newPassword = trim($_POST['password'] ?? '');

        if ($newPassword !== '') {
            if (strlen($newPassword) < 6) {
                flash('error', 'A nova senha deve ter pelo menos 6 caracteres.');
                back();
            }

            $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $user->update($updateData);

        $currentUser = auth();

        AuditLogService::log(
            'user.updated',
            (int) ($currentUser['id'] ?? 0),
            'user',
            (int) $user->id,
            'Usuário atualizou o usuário: ' . $user->email
        );

        unset($_SESSION['_old']);

        flash('success', 'Usuário atualizado com sucesso.');
        redirect('/users');
    }

    /**
     * Remove o registro informado após validações de segurança.
     */
    public function destroy(array $params): void
    {
        AdminMiddleware::handle();

        if (!verify_csrf_token($_POST['_csrf'] ?? null)) {
            flash('error', 'Token CSRF inválido.');
            back();
        }

        $id = (int) ($params['id'] ?? 0);
        $user = User::find($id);

        if (!$user) {
            flash('error', 'Usuário não encontrado.');
            redirect('/users');
        }

        $currentUser = auth();

        if ((int) ($currentUser['id'] ?? 0) === (int) $user->id) {
            flash('error', 'Você não pode excluir seu próprio usuário.');
            redirect('/users');
        }

        $deletedUserId = (int) $user->id;
        $deletedUserEmail = (string) $user->email;

        $user->delete();

        AuditLogService::log(
            'user.deleted',
            (int) ($currentUser['id'] ?? 0),
            'user',
            $deletedUserId,
            'Usuário excluiu o usuário: ' . $deletedUserEmail
        );

        flash('success', 'Usuário excluído com sucesso.');
        redirect('/users');
    }

    /**
     * Gera e envia um arquivo CSV respeitando os filtros atuais.
     */
    public function exportCsv(array $params = []): void
    {
        AdminMiddleware::handle();

        $search = trim($_GET['search'] ?? '');
        $role = trim($_GET['role'] ?? '');
        $sort = trim($_GET['sort'] ?? 'created_at');
        $direction = trim($_GET['direction'] ?? 'desc');

        $allowedSorts = ['name', 'email', 'created_at'];
        $allowedDirections = ['asc', 'desc'];
        $allowedRoles = ['admin', 'user'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, $allowedDirections, true)) {
            $direction = 'desc';
        }

        if ($role !== '' && !in_array($role, $allowedRoles, true)) {
            $role = '';
        }

        $query = User::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        $users = $query
            ->orderBy($sort, $direction)
            ->get(['id', 'name', 'email', 'role', 'created_at']);

        $filename = 'usuarios_' . date('Y-m-d_H-i-s') . '.csv';

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        if ($output === false) {
            http_response_code(500);
            echo 'Não foi possível gerar o arquivo CSV.';
            exit;
        }

        fwrite($output, "\xEF\xBB\xBF");
        fputcsv($output, ['ID', 'Nome', 'E-mail', 'Perfil', 'Criado em'], ';', '"', '\\');

        foreach ($users as $user) {
            fputcsv($output, [
                $user->id,
                $user->name,
                $user->email,
                $user->role,
                format_datetime($user->created_at),
            ], ';', '"', '\\');
        }

        fclose($output);
        exit;
    }
}
