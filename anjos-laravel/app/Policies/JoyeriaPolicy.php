<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Joyeria;

class JoyeriaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'cliente']);
    }

    public function view(User $user, Joyeria $joyeria): bool
    {
        return $user->hasAnyRole(['admin', 'cliente']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Joyeria $joyeria): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Joyeria $joyeria): bool
    {
        return $user->hasRole('admin');
    }
}