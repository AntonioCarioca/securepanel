<?php

declare(strict_types=1);

/**
 * Model Eloquent que representa a tabela audit_logs e seu relacionamento com usuário.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Eloquent que representa a tabela audit_logs e seu relacionamento com usuário.
 */
class AuditLog extends Model
{
    protected $table = 'audit_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    /**
     * Método user().
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
