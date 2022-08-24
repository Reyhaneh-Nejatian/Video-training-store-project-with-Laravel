<?php


namespace arghavan\Payment\Providers;

use arghavan\Payment\Events\PaymentWasSuccessful;
use arghavan\Payment\Listeners\AddSellersShareToHisAccount;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentWasSuccessful::class => [
            AddSellersShareToHisAccount::class,
        ]
    ];

    public function boot()
    {
        //
    }

    public function shouldDiscoverEvents()
    {
        return false;
    }
}
