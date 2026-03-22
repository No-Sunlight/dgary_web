<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StockCount;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockCountPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StockCount');
    }

    public function view(AuthUser $authUser, StockCount $stockCount): bool
    {
        return $authUser->can('View:StockCount');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StockCount');
    }

    public function update(AuthUser $authUser, StockCount $stockCount): bool
    {
        return $authUser->can('Update:StockCount');
    }

    public function delete(AuthUser $authUser, StockCount $stockCount): bool
    {
        return $authUser->can('Delete:StockCount');
    }

    public function restore(AuthUser $authUser, StockCount $stockCount): bool
    {
        return $authUser->can('Restore:StockCount');
    }

    public function forceDelete(AuthUser $authUser, StockCount $stockCount): bool
    {
        return $authUser->can('ForceDelete:StockCount');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StockCount');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StockCount');
    }

    public function replicate(AuthUser $authUser, StockCount $stockCount): bool
    {
        return $authUser->can('Replicate:StockCount');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StockCount');
    }

}