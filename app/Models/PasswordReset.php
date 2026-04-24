<?php

declare(strict_types=1);

/**
 * Model Eloquent que representa tokens de redefinição de senha.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Eloquent que representa tokens de redefinição de senha.
 */
class PasswordReset extends Model
{
    protected $table = 'password_resets';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'created_at',
    ];
}
