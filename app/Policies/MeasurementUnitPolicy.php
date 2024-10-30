<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MeasurementUnit;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeasurementUnitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_measurement::unit');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MeasurementUnit $measurementUnit): bool
    {
        return $user->can('view_measurement::unit');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_measurement::unit');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MeasurementUnit $measurementUnit): bool
    {
        return $user->can('update_measurement::unit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MeasurementUnit $measurementUnit): bool
    {
        return $user->can('delete_measurement::unit');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_measurement::unit');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, MeasurementUnit $measurementUnit): bool
    {
        return $user->can('force_delete_measurement::unit');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_measurement::unit');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, MeasurementUnit $measurementUnit): bool
    {
        return $user->can('restore_measurement::unit');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_measurement::unit');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, MeasurementUnit $measurementUnit): bool
    {
        return $user->can('replicate_measurement::unit');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_measurement::unit');
    }
}
