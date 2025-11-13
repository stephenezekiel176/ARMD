<?php

namespace App\Policies;

use App\Models\Personnel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PersonnelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->is_facilitator;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Personnel $personnel): bool
    {
        return $user->is_facilitator && $personnel->department_id === $user->department_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->is_facilitator;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Personnel $personnel): bool
    {
        return $user->is_facilitator && $personnel->department_id === $user->department_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Personnel $personnel): bool
    {
        return $user->is_facilitator && $personnel->department_id === $user->department_id;
    }
}
