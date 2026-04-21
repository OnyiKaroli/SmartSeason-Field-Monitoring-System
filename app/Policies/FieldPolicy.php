<?php

namespace App\Policies;

use App\Models\Field;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FieldPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Both admins and agents can view fields (agents see filtered list)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Field $field): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $field->assigned_agent_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Field $field): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Field $field): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can assign a field.
     */
    public function assign(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the status of a field.
     */
    public function updateStatus(User $user, Field $field): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isFieldAgent() && $field->assigned_agent_id === $user->id;
    }
}
