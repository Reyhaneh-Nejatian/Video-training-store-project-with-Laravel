<?php


namespace arghavan\Discount\Providers;


use arghavan\Discount\Models\Discount;
use arghavan\Discount\Policies\DiscountPolicy;
use arghavan\RolePermissions\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class DiscountServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/','Discounts');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/discount_routes.php');

        $this->loadJsonTranslationsFrom(__DIR__ .'/../Resources/Lang');

        Gate::policy(Discount::class,DiscountPolicy::class);
    }

    public function boot()
    {

        config()->set('sidebar.items.discounts', [
            "icon" => "i-discounts",
            "title" => "تخفیف ها",
            "url" => route('discounts.index'),
            "permission" => Permission::PERMISSION_MANAGE_DISCOUNT
        ]);
    }
}
