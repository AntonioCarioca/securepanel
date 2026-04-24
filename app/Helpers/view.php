<?php

declare(strict_types=1);

/**
 * Arquivo de helpers de apresentação usados pelas views e presenters.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

/**
 * Formata data/hora para o padrão brasileiro usado nas views.
 */
function format_datetime(mixed $value): string
{
    if (empty($value)) {
        return '-';
    }

    $timestamp = strtotime((string) $value);

    if ($timestamp === false) {
        return '-';
    }

    return date('d/m/Y H:i', $timestamp);
}

/**
 * Função auxiliar format_date_br().
 */
function format_date_br(mixed $value): string
{
    if (empty($value)) {
        return '-';
    }

    $timestamp = strtotime((string) $value);

    if ($timestamp === false) {
        return '-';
    }

    return date('d/m/Y', $timestamp);
}

/**
 * Função auxiliar formatDate().
 */
function formatDate(string $date): string
{
    return format_datetime($date);
}

/**
 * Função auxiliar user_role_label().
 */
function user_role_label(string $role): string
{
    return match ($role) {
        'admin' => 'ADMIN',
        default => 'USER',
    };
}

/**
 * Função auxiliar user_role_badge().
 */
function user_role_badge(string $role): array
{
    return match ($role) {
        'admin' => [
            'label' => 'ADMIN',
            'background' => '#e53935',
            'color' => '#ffffff',
        ],
        default => [
            'label' => 'USER',
            'background' => '#1e88e5',
            'color' => '#ffffff',
        ],
    };
}

/**
 * Função auxiliar audit_action_badge().
 */
function audit_action_badge(string $action): array
{
    $badges = [
        'auth.login' => [
            'label' => 'Login',
            'background' => '#dcfce7',
            'color' => '#166534',
        ],
        'auth.logout' => [
            'label' => 'Logout',
            'background' => '#e5e7eb',
            'color' => '#374151',
        ],
        'password.reset.requested' => [
            'label' => 'Reset solicitado',
            'background' => '#ede9fe',
            'color' => '#6d28d9',
        ],
        'password.reset.completed' => [
            'label' => 'Reset concluído',
            'background' => '#ddd6fe',
            'color' => '#5b21b6',
        ],
        'user.created' => [
            'label' => 'Usuário criado',
            'background' => '#dbeafe',
            'color' => '#1d4ed8',
        ],
        'user.updated' => [
            'label' => 'Usuário atualizado',
            'background' => '#fef3c7',
            'color' => '#92400e',
        ],
        'user.deleted' => [
            'label' => 'Usuário excluído',
            'background' => '#fee2e2',
            'color' => '#b91c1c',
        ],
        'api.user.created' => [
            'label' => 'API criou usuário',
            'background' => '#dbeafe',
            'color' => '#1d4ed8',
        ],
        'api.user.updated' => [
            'label' => 'API atualizou usuário',
            'background' => '#fef3c7',
            'color' => '#92400e',
        ],
        'api.user.deleted' => [
            'label' => 'API excluiu usuário',
            'background' => '#fee2e2',
            'color' => '#b91c1c',
        ],
    ];

    return $badges[$action] ?? [
        'label' => $action,
        'background' => '#e2e8f0',
        'color' => '#334155',
    ];
}

/**
 * Traduz target_type e target_id para texto amigável.
 */
function audit_target_label(?string $targetType, ?int $targetId = null): string
{
    $labels = [
        'user' => 'Usuário',
        'auth' => 'Autenticação',
        'password' => 'Senha',
        'session' => 'Sessão',
        'api' => 'API',
        'system' => 'Sistema',
        'audit_log' => 'Log',
    ];

    if (empty($targetType)) {
        $label = 'Sistema';
    } else {
        $label = $labels[$targetType] ?? ucfirst(str_replace('_', ' ', $targetType));
    }

    if ($targetId !== null) {
        $label .= ' #' . $targetId;
    }

    return $label;
}

/**
 * Função auxiliar build_url().
 */
function build_url(string $path, array $query = []): string
{
    $query = array_filter($query, static fn($value) => $value !== null && $value !== '');

    if ($query === []) {
        return $path;
    }

    return $path . '?' . http_build_query($query);
}

/**
 * Função auxiliar sort_url().
 */
function sort_url(string $path, string $column, string $currentSort, string $currentDirection, array $query = []): string
{
    $newDirection = 'asc';

    if ($currentSort === $column) {
        $newDirection = $currentDirection === 'asc' ? 'desc' : 'asc';
    }

    return build_url($path, array_merge($query, [
        'sort' => $column,
        'direction' => $newDirection,
        'page' => 1,
    ]));
}

/**
 * Função auxiliar sort_indicator().
 */
function sort_indicator(string $column, string $currentSort, string $currentDirection): string
{
    if ($currentSort !== $column) {
        return '';
    }

    return $currentDirection === 'asc' ? ' ↑' : ' ↓';
}

/**
 * Função auxiliar pagination_items().
 */
function pagination_items(int $currentPage, int $totalPages): array
{
    if ($totalPages <= 7) {
        return range(1, $totalPages);
    }

    $items = [1];

    if ($currentPage <= 4) {
        for ($i = 2; $i <= 5; $i++) {
            $items[] = $i;
        }

        $items[] = '...';
        $items[] = $totalPages;

        return $items;
    }

    if ($currentPage >= $totalPages - 3) {
        $items[] = '...';

        for ($i = $totalPages - 4; $i < $totalPages; $i++) {
            $items[] = $i;
        }

        $items[] = $totalPages;

        return $items;
    }

    $items[] = '...';

    for ($i = $currentPage - 1; $i <= $currentPage + 1; $i++) {
        $items[] = $i;
    }

    $items[] = '...';
    $items[] = $totalPages;

    return $items;
}

/**
 * Função auxiliar pagination_view_data().
 */
function pagination_view_data(string $path, int $currentPage, int $totalPages, array $query = []): array
{
    return [
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
        'items' => pagination_items($currentPage, $totalPages),
        'previousUrl' => $currentPage > 1 ? build_url($path, array_merge($query, ['page' => $currentPage - 1])) : null,
        'nextUrl' => $currentPage < $totalPages ? build_url($path, array_merge($query, ['page' => $currentPage + 1])) : null,
        'url' => static fn(int $page): string => build_url($path, array_merge($query, ['page' => $page])),
    ];
}

/**
 * Função auxiliar user_active_filters().
 */
function user_active_filters(string $search, string $role, string $sort, string $direction): array
{
    $filters = [];

    if ($search !== '') {
        $filters[] = 'Busca: "' . $search . '"';
    }

    if ($role !== '') {
        $filters[] = 'Perfil: ' . strtoupper($role);
    }

    if ($sort !== '' && !($sort === 'created_at' && $direction === 'desc')) {
        $sortLabels = [
            'name' => 'Nome',
            'email' => 'E-mail',
            'created_at' => 'Data',
        ];

        $directionLabels = [
            'asc' => 'crescente',
            'desc' => 'decrescente',
        ];

        $filters[] = 'Ordenação: ' . ($sortLabels[$sort] ?? $sort) . ' (' . ($directionLabels[$direction] ?? $direction) . ')';
    }

    return $filters;
}

/**
 * Função auxiliar audit_active_filters().
 */
function audit_active_filters(string $search, string $action, string $dateFrom, string $dateTo): array
{
    $filters = [];

    if ($search !== '') {
        $filters[] = 'Busca: "' . $search . '"';
    }

    if ($action !== '') {
        $filters[] = 'Ação: ' . $action;
    }

    if ($dateFrom !== '') {
        $filters[] = 'De: ' . format_date_br($dateFrom);
    }

    if ($dateTo !== '') {
        $filters[] = 'Até: ' . format_date_br($dateTo);
    }

    return $filters;
}

/**
 * Função auxiliar old_value().
 */
function old_value(string $key, mixed $default = ''): string
{
    return (string) old($key, $default);
}

/**
 * Função auxiliar selected_attr().
 */
function selected_attr(mixed $current, mixed $expected): string
{
    return (string) $current === (string) $expected ? 'selected' : '';
}

/**
 * Função auxiliar checked_attr().
 */
function checked_attr(mixed $current, mixed $expected): string
{
    return (string) $current === (string) $expected ? 'checked' : '';
}

/**
 * Função auxiliar is_admin_user().
 */
function is_admin_user(?array $user): bool
{
    return ($user['role'] ?? 'user') === 'admin';
}

/**
 * Função auxiliar auth_nav_links().
 */
function auth_nav_links(?array $authUser): array
{
    if (empty($authUser)) {
        return [];
    }

    $links = [
        ['label' => 'Dashboard', 'url' => '/dashboard'],
    ];

    if (is_admin_user($authUser)) {
        $links[] = ['label' => 'Usuários', 'url' => '/users'];
        $links[] = ['label' => 'Audit Logs', 'url' => '/audit-logs'];
    }

    return $links;
}

/**
 * Função auxiliar dashboard_user_data().
 */
function dashboard_user_data(?array $user): array
{
    return [
        'name' => (string) ($user['name'] ?? 'Usuário'),
        'email' => (string) ($user['email'] ?? ''),
        'role' => (string) ($user['role'] ?? 'user'),
        'isAdmin' => is_admin_user($user),
    ];
}

/**
 * Função auxiliar user_form_data().
 */
function user_form_data(mixed $user = null): array
{
    $isEdit = $user !== null;

    return [
        'title' => $isEdit ? 'Editar usuário' : 'Novo usuário',
        'action' => $isEdit ? '/users/' . (int) $user->id . '/update' : '/users',
        'button' => $isEdit ? 'Atualizar' : 'Salvar',
        'passwordLabel' => $isEdit ? 'Nova senha (opcional)' : 'Senha',
        'passwordRequired' => !$isEdit,
        'name' => old_value('name', $isEdit ? $user->name : ''),
        'email' => old_value('email', $isEdit ? $user->email : ''),
        'role' => old_value('role', $isEdit ? $user->role : 'user'),
    ];
}
