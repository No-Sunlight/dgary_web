<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InventoryCount;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryCountPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InventoryCount');
    }

    public function view(AuthUser $authUser, InventoryCount $inventoryCount): bool
    {
        return $authUser->can('View:InventoryCount');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InventoryCount');
    }

    public function update(AuthUser $authUser, InventoryCount $inventoryCount): bool
    {
        return $authUser->can('Update:InventoryCount');
    }

    public function delete(AuthUser $authUser, InventoryCount $inventoryCount): bool
    {
        return $authUser->can('Delete:InventoryCount');
    }

    public function restore(AuthUser $authUser, InventoryCount $inventoryCount): bool
    {
        return $authUser->can('Restore:InventoryCount');
    }

    public function forceDelete(AuthUser $authUser, InventoryCount $inventoryCount): bool
    {
        return $authUser->can('ForceDelete:InventoryCount');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InventoryCount');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InventoryCount');
    }

    public function replicate(AuthUser $authUser, InventoryCount $inventoryCount): bool
    {
        return $authUser->can('Replicate:InventoryCount');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InventoryCount');
    }

}