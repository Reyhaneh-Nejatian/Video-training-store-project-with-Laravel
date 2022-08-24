<?php

namespace arghavan\RolePermissions\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (\arghavan\RolePermissions\Models\Permission::$permissions as $permission){

            Permission::findOrCreate($permission);
        }

        foreach (\arghavan\RolePermissions\Models\Role::$roles as $name => $permission){

            Role::findOrCreate($name)->givePermissionTo($permission);

        }

//        Role::findOrCreate('manage role_permissions')->givePermissionTo(['manage role_permissions']);
//        Role::findOrCreate('manage categories')->givePermissionTo(['manage categories']);

    }
}
