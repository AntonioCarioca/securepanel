<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\AuditLog;

class AuditLogPresenter
{
    public function __construct(private AuditLog $log)
    {
    }

    public function id(): int
    {
        return (int) $this->log->id;
    }

    public function action(): string
    {
        return (string) $this->log->action;
    }

    public function actionBadge(): array
    {
        return audit_action_badge($this->action());
    }

    public function userName(): string
    {
        return $this->log->user?->name ?? 'Usuário removido / não encontrado';
    }

    public function userEmail(): string
    {
        return $this->log->user?->email ?? '-';
    }

    public function targetLabel(): string
    {
        return audit_target_label(
            $this->log->target_type,
            !empty($this->log->target_id) ? (int) $this->log->target_id : null
        );
    }

    public function description(): string
    {
        return (string) ($this->log->description ?? '-');
    }

    public function ipAddress(): string
    {
        return (string) ($this->log->ip_address ?? '-');
    }

    public function createdAt(): string
    {
        return format_datetime($this->log->created_at);
    }
}
