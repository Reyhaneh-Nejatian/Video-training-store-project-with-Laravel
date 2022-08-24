<?php

namespace arghavan\Pyment\Policies;

use arghavan\RolePermissions\Models\Permission;
use arghavan\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettlementPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $user)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_SETTLEMENTS) ||
            Permission::PERMISSION_TEACH) return true;

        return false;
    }
    public function manage(User $user)
    {
        if($user->hasPermissionTo(\arghavan\RolePermissions\Models\Permission::PERMISSION_MANAGE_SETTLEMENTS)) return true;

        return false;
    }

    public function create($user)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_TEACH)) return true;

        return false;
    }
}
