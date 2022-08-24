<?php

namespace arghavan\Comment\Listeners;

use arghavan\Comment\Notifications\CommentSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CommentSubmittedListener
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
        if($event->comment->comment_id && $event->comment->user_id !== $event->comment->comment->user_id)
            $event->comment->comment->user->notify(new CommentSubmittedNotification($event->comment));
    }
}
