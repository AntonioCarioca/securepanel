<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\User;

class UserPresenter
{
    public function __construct(private User $user)
    {
    }

    public function id(): int
    {
        return (int) $this->user->id;
    }

    public function name(): string
    {
        return (string) $this->user->name;
    }

    public function email(): string
    {
        return (string) $this->user->email;
    }

    public function role(): string
    {
        return (string) $this->user->role;
    }

    public function roleBadge(): array
    {
        return user_role_badge($this->role());
    }

    public function createdAt(): string
    {
        return format_datetime($this->user->created_at);
    }

    public function editUrl(): string
    {
        return '/users/' . $this->id() . '/edit';
    }

    public function deleteUrl(): string
    {
        return '/users/' . $this->id() . '/delete';
    }
}
