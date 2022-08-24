<?php

namespace arghavan\Payment\Providers;

use arghavan\Payment\Gateways\Gateway;
use arghavan\Payment\Gateways\Zarinpal\ZarinpalAdaptor;
use arghavan\Payment\Models\Settlement;
use arghavan\Pyment\Policies\SettlementPolicy;
use arghavan\RolePermissions\Models\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProviders extends ServiceProvider
{
    public $namespace = "arghavan\Payment\Http\Controllers";
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        Route::middleware("web")->namespace($this->namespace)->group(__DIR__ . "/../Routes/payment_routes.php");
        Route::middleware(["web","auth"])->namespace($this->namespace)->group(__DIR__ . "/../Routes/settlement_routes.php");

        $this->loadViewsFrom(__DIR__.'/../Resources/Views','Payment');

        $this->loadJsonTranslationsFrom(__DIR__.'/../Resources/Lang');

        Gate::policy(Settlement::class,SettlementPolicy::class);
    }

    public function boot()
    {
        $this->app->singleton(Gateway::class,function ($app){
            return new ZarinpalAdaptor();
        });

        config()->set('sidebar.items.payments',[
            'icon' => 'i-transactions',
            'title' => 'تراکنش ها',
            'url' => route('payments.index'),
            'permission' => Permission::PERMISSION_MANAGE_PAYMENTS,
        ]);

        config()->set('sidebar.items.my-purchases', [
            "icon" => "i-my__purchases",
            "title" => "خریدهای من",
            "url" => route('purchases.index'),
        ]);

        config()->set('sidebar.items.settlements', [
            "icon" => "i-checkouts",
            "title" => " تسویه حساب ها",
            "url" => route('settlements.index'),
            "permission" => [
                Permission::PERMISSION_TEACH,
                Permission::PERMISSION_MANAGE_SETTLEMENTS
            ]
        ]);
        config()->set('sidebar.items.settlementsRequest', [
            "icon" => "i-checkout__request",
            "title" => "درخواست تسویه",
            "url" => route('settlements.create'),
            "permission" => [
                Permission::PERMISSION_TEACH,
            ]
        ]);
    }
}
