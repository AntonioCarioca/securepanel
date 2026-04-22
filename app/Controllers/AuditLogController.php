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
            ->orderBy('id', 'desc')
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
        ]);
    }
}
