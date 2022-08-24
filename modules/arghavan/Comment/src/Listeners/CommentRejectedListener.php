<?php

namespace arghavan\Comment\Listeners;

use arghavan\Comment\Notifications\CommentApprovedNotification;
use arghavan\Comment\Notifications\CommentRejectedNotification;
use arghavan\Comment\Notifications\CommentSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentRejectedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->comment->user->notify(new CommentRejectedNotification($event->comment));
    }
}
