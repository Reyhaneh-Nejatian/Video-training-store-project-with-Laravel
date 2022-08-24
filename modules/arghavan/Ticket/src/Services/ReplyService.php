<?php


namespace arghavan\Ticket\Services;


use arghavan\Media\Services\MediaFileService;
use arghavan\Ticket\Database\Repositories\ReplyRepo;
use arghavan\Ticket\Database\Repositories\TicketRepo;
use arghavan\Ticket\Models\Ticket;
use Illuminate\Http\UploadedFile;

class ReplyService
{
    static function store(Ticket $ticket,$reply,$attachment)
    {
        $repo = new ReplyRepo();
        $ticketRepo = new TicketRepo();

        $media_id = null;
        if($attachment && ($attachment instanceof UploadedFile))
        {
            $media_id = MediaFileService::privateUpload($attachment)->id;
        }

        $reply = $repo->store($ticket->id,$reply,$media_id);

        if($reply->user_id != $ticket->user_id)
        {
            $ticketRepo->setStatus($ticket->id,Ticket::STATUS_REPLIED);
        }else{
            $ticketRepo->setStatus($ticket->id,Ticket::STATUS_OPEN);
        }

        return $reply;

    }
}
