<?php

namespace arghavan\Course\Policies;

use arghavan\RolePermissions\Models\Permission;
use arghavan\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeasonPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function manage(User $user){

        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES);
    }

    public function create($user)
    {

        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) ||
            $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES)) return true;
    }

    public function edit($user,$season){

        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;

            return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) &&
                $season->course->teacher->id == $user->id;
    }

    public function delete($user,$season){

        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;

        return $user->hasPermissionTo(Permission::PERMISSION_MANAGE_OWN_COURSES) &&
            $season->course->teacher->id == $user->id;
    }

    public function change_confirmation_status($user){

        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES)) return true;

        return null;
    }
}


//5 arghavan\User\Models\User    1
