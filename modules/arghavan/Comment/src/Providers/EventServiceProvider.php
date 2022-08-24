<?php


namespace arghavan\Comment\Providers;

use arghavan\Comment\Events\CommentApprovedEvent;
use arghavan\Comment\Events\CommentRejectedEvent;
use arghavan\Comment\Events\CommentSubmittedEvent;
use arghavan\Comment\Listeners\CommentApprovedListener;
use arghavan\Comment\Listeners\CommentRejectedListener;
use arghavan\Comment\Listeners\CommentSubmittedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CommentSubmittedEvent::class => [
            CommentSubmittedListener::class,
        ],
        CommentApprovedEvent::class => [
            CommentApprovedListener::class,
        ],
        CommentRejectedEvent::class => [
            CommentRejectedListener::class,
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
