<?php

namespace arghavan\Comment\Notifications;

use arghavan\Comment\Mail\CommentSubmittedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\Telegram;
use NotificationChannels\Telegram\TelegramMessage;

class CommentSubmittedNotification extends Notification
{
    use Queueable;

    public $comment;
    public function __construct($comment)
    {
        $this->comment = $comment;
    }


    public function via($notifiable)
    {
        $channels = [
            "mail",
            "database"
        ];
        if (!empty($notifiable->telegram)) $channels[] = telegram::class;

        return $channels;
    }


    public function toMail($notifiable)
    {
        return (new CommentSubmittedMail($this->comment))->to($notifiable->email);
    }

    public function toTelegram($notifiable)
    {
        if (!empty($notifiable->telegram))
        return TelegramMessage::create()
            // Optional recipient user id.
            ->to($notifiable->telegram)
            // Markdown supported.u
            ->content("یک دیدگاه جدید برای دوره ی شما در وب آموز ارسال شده است.")
            ->button('مشاهده دوره', $this->comment->commentable->path())
            ->button('مدیریت دیدگاه ها', route("comments.index"));
    }


    public function toArray($notifiable)
    {
        return [
            "message" => "دیدگاه جدید برای دوره ی شما ثبت شده است.",
            "url" => route("comments.index"),
        ];
    }
}
