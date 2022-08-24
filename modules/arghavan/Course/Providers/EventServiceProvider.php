<?php


namespace arghavan\Course\Providers;

use arghavan\Course\Listeners\RegisterUserInTheCourse;
use arghavan\Payment\Events\PaymentWasSuccessful;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentWasSuccessful::class => [  //event
            RegisterUserInTheCourse::class,  //listener
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
