<?php


namespace arghavan\User\Providers;

use arghavan\RolePermissions\Models\Permission;
use arghavan\User\Database\Seeders\UsersTableSeeder;
use arghavan\User\Http\Middleware\StoreUserIp;
use arghavan\User\Models\User;
use arghavan\User\Policies\UserPolicy;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/user_routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadViewsFrom(__DIR__.'/../Resources/Views','User');

        $this->loadJsonTranslationsFrom(__DIR__.'/../Resources/Lang');



        Factory::guessFactoryNamesUsing(static function (string $user) {
            return 'arghavan\User\Database\Factories\\' . class_basename($user) .'Factory';
        });


        config()->set('auth.providers.users.model',User::class);
        Gate::policy(User::class,UserPolicy::class);

        DatabaseSeeder::$seeders[] = UsersTableSeeder::class;
    }

    public function boot(){

        $this->app['router']->pushMiddlewareToGroup('web',StoreUserIp::class);

        config()->set('sidebar.items.users', [
            "icon" => "i-users",
            "title" => "کاربران",
            "url" => route('users.index'),
            "permission" => Permission::PERMISSION_MANAGE_USERS
        ]);

        config()->set('sidebar.items.usersInformation', [
            "icon" => "i-user__information",
            "title" => "اطلاعات کاربری",
            "url" => route('users.profile')
        ]);


    }

}



