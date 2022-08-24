<?php

namespace arghavan\Pyment\Policies;

use arghavan\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    public function manage(User $user)
    {
        if($user->hasPermissionTo(\arghavan\RolePermissions\Models\Permission::PERMISSION_MANAGE_PAYMENTS)) return true;

        return false;
    }
}
