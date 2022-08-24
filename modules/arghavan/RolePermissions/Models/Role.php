<?php


namespace arghavan\RolePermissions\Models;


class Role extends \Spatie\Permission\Models\Role
{

    const ROLE_TEACHER = 'teacher';
    const ROLE_SUPER_AMIN = 'super admin';
    const ROLE_STUDENT = 'student';
    static $roles = [
        self::ROLE_TEACHER => [
            Permission::PERMISSION_TEACH
        ],
        self::ROLE_SUPER_AMIN => [
            Permission::PERMISSION_SUPER_ADMIN
        ],
        self::ROLE_STUDENT => [

        ]
    ];
}
