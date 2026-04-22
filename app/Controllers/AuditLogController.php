<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Middleware\AdminMiddleware;
use App\Models\AuditLog;

class AuditLogController
{
    public function index(array $params = []): void
    {
        AdminMiddleware::handle();

        $search = trim($_GET['search'] ?? '');
        $action = trim($_GET['action'] ?? '');
        $dateFrom = trim($_GET['date_from'] ?? '');
        $dateTo = trim($_GET['date_to'] ?? '');
        $sort = trim($_GET['sort'] ?? 'created_at');
        $direction = trim($_GET['direction'] ?? 'desc');
        $page = (int) ($_GET['page'] ?? 1);
        $perPage = 15;

        if ($page < 1) {
            $page = 1;
        }

        $query = AuditLog::query()->with('user');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('ip_address', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%')
                                ->orWhere('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $allowedSorts = ['id', 'action', 'ip_address', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, $allowedDirections, true)) {
            $direction = 'desc';
        }

        if ($action !== '') {
            $query->where('action', $action);
        }

        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $total = (clone $query)->count();
        $totalPages = (int) ceil($total / $perPage);

        if ($totalPages < 1) {
            $totalPages = 1;
        }

        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $logs = $query
            ->orderBy($sort, $direction)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $actions = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action')
            ->toArray();

        View::render('audit-logs/index', [
            'logs' => $logs,
            'search' => $search,
            'action' => $action,
            'actions' => $actions,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'total' => $total,
            'totalPages' => $totalPages,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function exportCsv(array $params = []): void
    {
        AdminMiddleware::handle();

        $search = trim($_GET['search'] ?? '');
        $action = trim($_GET['action'] ?? '');
        $dateFrom = trim($_GET['date_from'] ?? '');
        $dateTo = trim($_GET['date_to'] ?? '');
        $sort = trim($_GET['sort'] ?? 'created_at');
        $direction = trim($_GET['direction'] ?? 'desc');

        $allowedSorts = ['id', 'action', 'ip_address', 'created_at'];
        $allowedDirections = ['asc', 'desc'];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, $allowedDirections, true)) {
            $direction = 'desc';
        }

        $query = AuditLog::query()->with('user');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', '%' . $search . '%')
                  ->orWhere('ip_address', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%')
                                ->orWhere('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if ($action !== '') {
            $query->where('action', $action);
        }

        if ($dateFrom !== '') {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo !== '') {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $logs = $query
            ->orderBy($sort, $direction)
            ->get();

        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';

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

        fputcsv(
            $output,
            ['ID', 'Ação', 'Usuário', 'E-mail', 'Alvo', 'Descrição', 'IP', 'Data'],
            ';',
            '"',
            '\\'
        );

        foreach ($logs as $log) {
            $userName = $log->user?->name ?? 'Usuário removido / não encontrado';
            $userEmail = $log->user?->email ?? '-';

            $targetType = (string) ($log->target_type ?? '');
            $targetId = !empty($log->target_id) ? (int) $log->target_id : null;

            $targetLabels = [
                'user' => 'Usuário',
                'auth' => 'Autenticação',
                'password' => 'Senha',
                'session' => 'Sessão',
                'api' => 'API',
                'system' => 'Sistema',
                'audit_log' => 'Log',
            ];

            if ($targetType === '') {
                $targetLabel = 'Sistema';
            } else {
                $targetLabel = $targetLabels[$targetType] ?? ucfirst(str_replace('_', ' ', $targetType));
            }

            if ($targetId !== null) {
                $targetLabel .= ' #' . $targetId;
            }

            fputcsv(
                $output,
                [
                    $log->id,
                    $log->action,
                    $userName,
                    $userEmail,
                    $targetLabel,
                    (string) ($log->description ?? '-'),
                    (string) ($log->ip_address ?? '-'),
                    date('d/m/Y H:i', strtotime((string) $log->created_at)),
                ],
                ';',
                '"',
                '\\'
            );
        }

        fclose($output);
        exit;
    }
}
