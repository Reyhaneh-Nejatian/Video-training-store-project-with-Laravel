<?php

namespace arghavan\RolePermissions\Providers;

use arghavan\RolePermissions\Database\Seeders\RolePermissionTableSeeder;
use arghavan\RolePermissions\Models\Permission;
use arghavan\RolePermissions\Models\Role;
use arghavan\RolePermissions\Policies\RolePermissionsPolicy;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class RolePermissionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/role_permission.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

//        $this->loadFactoriesFrom(__DIR__.'/../Database/Factories');

        $this->loadViewsFrom(__DIR__.'/../Resources/Views','RolePermissions');

//        $this->mergeConfigFrom(__DIR__.'/../Config/sidebar.php','sidebar');

        $this->loadJsonTranslationsFrom(__DIR__.'/../Resources/Lang');

        DatabaseSeeder::$seeders[] = RolePermissionTableSeeder::class;

        Gate::policy(Role::class,RolePermissionsPolicy::class);

        Gate::before(function ($user){
            return $user->hasPermissionTo(Permission::PERMISSION_SUPER_ADMIN) ? true : null;
        });
    }

    public function boot()
    {
        config()->set('sidebar.items.role-permissions',[
            'icon' => 'i-role-permissions',
            'title' => 'نقش های کاربری',
            'url' => route('role-permissions.index'),
            'permission' => Permission::PERMISSION_MANAGE_ROLE_PERMISSIONS,
        ]);
    }
}
