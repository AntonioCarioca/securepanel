<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Middleware\AdminMiddleware;
use App\Models\AuditLog;
use App\Presenters\AuditLogPresenter;

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
            ->get()
            ->map(fn(AuditLog $log) => new AuditLogPresenter($log))
            ->all();

        $actions = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action')
            ->toArray();

        $baseQuery = [
            'search' => $search,
            'action' => $action,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'sort' => $sort,
            'direction' => $direction,
        ];

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
            'activeFilters' => audit_active_filters($search, $action, $dateFrom, $dateTo),
            'exportUrl' => build_url('/audit-logs/export', $baseQuery),
            'pagination' => pagination_view_data('/audit-logs', $page, $totalPages, $baseQuery),
            'sortUrls' => [
                'id' => sort_url('/audit-logs', 'id', $sort, $direction, $baseQuery),
                'action' => sort_url('/audit-logs', 'action', $sort, $direction, $baseQuery),
                'ip_address' => sort_url('/audit-logs', 'ip_address', $sort, $direction, $baseQuery),
                'created_at' => sort_url('/audit-logs', 'created_at', $sort, $direction, $baseQuery),
            ],
            'sortIndicators' => [
                'id' => sort_indicator('id', $sort, $direction),
                'action' => sort_indicator('action', $sort, $direction),
                'ip_address' => sort_indicator('ip_address', $sort, $direction),
                'created_at' => sort_indicator('created_at', $sort, $direction),
            ],
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
            fputcsv(
                $output,
                [
                    $log->id,
                    $log->action,
                    $log->user?->name ?? 'Usuário removido / não encontrado',
                    $log->user?->email ?? '-',
                    audit_target_label(
                        $log->target_type,
                        !empty($log->target_id) ? (int) $log->target_id : null
                    ),
                    (string) ($log->description ?? '-'),
                    (string) ($log->ip_address ?? '-'),
                    format_datetime($log->created_at),
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
