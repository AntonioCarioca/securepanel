<?php

declare(strict_types=1);

/**
 * Presenter que prepara dados de usuário para exibição, mantendo lógica fora da view.
 *
 * Comentado para estudo: os comentários explicam o papel do arquivo e os pontos
 * principais do fluxo, sem alterar a lógica original da aplicação.
 */

namespace App\Presenters;

use App\Models\User;

/**
 * Presenter que prepara dados de usuário para exibição, mantendo lógica fora da view.
 */
class UserPresenter
{
    /**
     * Método __construct().
     */
    public function __construct(private User $user)
    {
    }

    /**
     * Método id().
     */
    public function id(): int
    {
        return (int) $this->user->id;
    }

    /**
     * Método name().
     */
    public function name(): string
    {
        return (string) $this->user->name;
    }

    /**
     * Método email().
     */
    public function email(): string
    {
        return (string) $this->user->email;
    }

    /**
     * Método role().
     */
    public function role(): string
    {
        return (string) $this->user->role;
    }

    /**
     * Método roleBadge().
     */
    public function roleBadge(): array
    {
        return user_role_badge($this->role());
    }

    /**
     * Método createdAt().
     */
    public function createdAt(): string
    {
        return format_datetime($this->user->created_at);
    }

    /**
     * Método editUrl().
     */
    public function editUrl(): string
    {
        return '/users/' . $this->id() . '/edit';
    }

    /**
     * Método deleteUrl().
     */
    public function deleteUrl(): string
    {
        return '/users/' . $this->id() . '/delete';
    }
}
