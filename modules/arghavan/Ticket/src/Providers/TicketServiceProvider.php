<?php

namespace arghavan\Ticket\Providers;


use arghavan\Ticket\Models\Reply;
use arghavan\Ticket\Models\Ticket;
use arghavan\Ticket\Policies\ReplyPolicy;
use arghavan\Ticket\Policies\TicketPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class TicketServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Route/tickets_routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/Views','Tickets');

        Gate::policy(Ticket::class,TicketPolicy::class);
        Gate::policy(Reply::class,ReplyPolicy::class);
    }

    public function boot()
    {
        config()->set('sidebar.items.tickets', [
            "icon" => "i-tickets",
            "title" => "تیکت های پشتیبانی",
            "url" => route('tickets.index'),
        ]);
    }
}
