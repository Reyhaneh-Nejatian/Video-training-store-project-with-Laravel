<?php

namespace arghavan\Discount\Policies;

use arghavan\RolePermissions\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountPolicy
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

    public function manage($user)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_DISCOUNT)) return true;
    }
}
