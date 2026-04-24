<?php

declare(strict_types=1);

/**
 * Model Eloquent que representa a tabela users.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model Eloquent que representa a tabela users.
 */
class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];
}
