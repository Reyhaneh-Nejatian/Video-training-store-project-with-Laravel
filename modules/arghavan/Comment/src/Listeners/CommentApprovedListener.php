<?php

namespace arghavan\Comment\Listeners;

use arghavan\Comment\Notifications\CommentApprovedNotification;
use arghavan\Comment\Notifications\CommentSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class CommentApprovedListener
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
        if($event->comment->user_id !== $event->comment->commentable->teacher->id)
            $user = $event->comment->commentable->teacher;
            $user->notify(new CommentApprovedNotification($event->comment));


//            dd($event->comment->commentable->teacher);
//            Notification::send($event->comment->commentable->teacher, new CommentApprovedNotification($event->comment));
//            $event->comment->commentable->teacher->notify(new CommentSubmittedNotification($event->comment));

        $event->comment->user->notify(new CommentApprovedNotification($event->comment));
//        dd($event->comment->user);

//        Notification::send($event->comment->user, new CommentApprovedNotification($event->comment));
    }
}
