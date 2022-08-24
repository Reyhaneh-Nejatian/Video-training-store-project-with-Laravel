<?php

namespace arghavan\Comment\Providers;

use arghavan\Comment\Models\Comment;
use arghavan\Comment\Policies\CommentPolicy;
use arghavan\RolePermissions\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CommentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views','Comments');
        $this->loadRoutesFrom(__DIR__.'/../Routes/comments_routes.php');
        $this->loadJsonTranslationsFrom(__DIR__.'/../Resources/Lang');

        Gate::policy(Comment::class,CommentPolicy::class);
    }

    public function boot()
    {
        config()->set('sidebar.items.comments', [
            "icon" => "i-comments",
            "title" => "نظرات",
            "url" => route('comments.index'),
            "permission" => [Permission::PERMISSION_MANAGE_COMMENTS, Permission::PERMISSION_TEACH]
        ]);
    }
}
