<?php

declare(strict_types=1);

/**
 * Serviço responsável por registrar eventos importantes na tabela audit_logs.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Services;

use App\Models\AuditLog;

/**
 * Serviço responsável por registrar eventos importantes na tabela audit_logs.
 */
class AuditLogService
{
    public static function log(
        string $action,
        ?int $userId = null,
        ?string $targetType = null,
        ?int $targetId = null,
        ?string $description = null
    ): void {
        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'description' => $description,
            'ip_address' => self::ipAddress(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private static function ipAddress(): ?string
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $parts = explode(',', (string) $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($parts[0]);
        }

        return $_SERVER['REMOTE_ADDR'] ?? null;
    }
}
