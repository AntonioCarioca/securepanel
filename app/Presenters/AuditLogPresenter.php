<?php

declare(strict_types=1);

/**
 * Presenter que prepara dados de audit log para exibição, mantendo lógica fora da view.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Presenters;

use App\Models\AuditLog;

/**
 * Presenter que prepara dados de audit log para exibição, mantendo lógica fora da view.
 */
class AuditLogPresenter
{
    /**
     * Método __construct().
     */
    public function __construct(private AuditLog $log)
    {
    }

    /**
     * Método id().
     */
    public function id(): int
    {
        return (int) $this->log->id;
    }

    /**
     * Método action().
     */
    public function action(): string
    {
        return (string) $this->log->action;
    }

    /**
     * Método actionBadge().
     */
    public function actionBadge(): array
    {
        return audit_action_badge($this->action());
    }

    /**
     * Método userName().
     */
    public function userName(): string
    {
        return $this->log->user?->name ?? 'Usuário removido / não encontrado';
    }

    /**
     * Método userEmail().
     */
    public function userEmail(): string
    {
        return $this->log->user?->email ?? '-';
    }

    /**
     * Método targetLabel().
     */
    public function targetLabel(): string
    {
        return audit_target_label(
            $this->log->target_type,
            !empty($this->log->target_id) ? (int) $this->log->target_id : null
        );
    }

    /**
     * Método description().
     */
    public function description(): string
    {
        return (string) ($this->log->description ?? '-');
    }

    /**
     * Obtém o IP aproximado do usuário, considerando proxy reverso.
     */
    public function ipAddress(): string
    {
        return (string) ($this->log->ip_address ?? '-');
    }

    /**
     * Método createdAt().
     */
    public function createdAt(): string
    {
        return format_datetime($this->log->created_at);
    }
}
