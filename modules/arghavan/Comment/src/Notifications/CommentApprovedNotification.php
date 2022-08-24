<?php

namespace arghavan\Comment\Notifications;

use arghavan\Comment\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\Telegram;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramUpdates;

class CommentApprovedNotification extends Notification
{
    use Queueable;

    public $comment;
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }


    public function via($notifiable)
    {
//        return [TelegramChannel::class];
        $channels[] = 'mail';
        $channels[] = "database";
        if (!empty($notifiable->telegram)) $channels[] = TelegramChannel::class;

        return $channels;
    }


    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    public function toTelegram($notifiable)
    {
        if (!empty($notifiable->telegram))
            return TelegramMessage::create()
                ->to('544591032')
                ->content("دیدگاه شما تایید شد.")
                ->button('مشاهده دوره', 'google.com');

//                ->button('مشاهده دوره', $this->comment->commentable->path());
//            dd($m);

//        $updates = TelegramUpdates::create()->limit(2)
//            ->get();
//        dd(
//            $updates
//        );
    }




    public function toArray($notifiable)
    {
        return [
            "message" => "دیدگاه شما تایید شد.",
            "url" => $this->comment->commentable->path(),
        ];
    }
}
