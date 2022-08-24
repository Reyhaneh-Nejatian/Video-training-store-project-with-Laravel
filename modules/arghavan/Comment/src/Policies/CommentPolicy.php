<?php

namespace arghavan\Comment\Policies;

use arghavan\RolePermissions\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;
use arghavan\User\Models\User;
use phpDocumentor\Reflection\Types\True_;

class CommentPolicy
{
    use HandlesAuthorization;


    public function manage($user)
    {
        if ($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COMMENTS)) return true;
        return null;
    }

    public function index($user)
    {
        if($user->hasAnyPermission(Permission::PERMISSION_MANAGE_COMMENTS,
            Permission::PERMISSION_TEACH)) return true;

        return null;
    }

    public function view($user,$comment)
    {
        if($user->hasPermissionTo(Permission::PERMISSION_MANAGE_COMMENTS) ||
            $user->id == $comment->commentable->teacher_id) return true;

        return null;
    }
}
