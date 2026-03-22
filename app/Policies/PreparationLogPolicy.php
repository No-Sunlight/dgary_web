<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PreparationLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class PreparationLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PreparationLog');
    }

    public function view(AuthUser $authUser, PreparationLog $preparationLog): bool
    {
        return $authUser->can('View:PreparationLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PreparationLog');
    }

    public function update(AuthUser $authUser, PreparationLog $preparationLog): bool
    {
        return $authUser->can('Update:PreparationLog');
    }

    public function delete(AuthUser $authUser, PreparationLog $preparationLog): bool
    {
        return $authUser->can('Delete:PreparationLog');
    }

    public function restore(AuthUser $authUser, PreparationLog $preparationLog): bool
    {
        return $authUser->can('Restore:PreparationLog');
    }

    public function forceDelete(AuthUser $authUser, PreparationLog $preparationLog): bool
    {
        return $authUser->can('ForceDelete:PreparationLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PreparationLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PreparationLog');
    }

    public function replicate(AuthUser $authUser, PreparationLog $preparationLog): bool
    {
        return $authUser->can('Replicate:PreparationLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PreparationLog');
    }

}