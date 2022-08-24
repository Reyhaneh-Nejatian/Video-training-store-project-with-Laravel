<?php


namespace arghavan\Ticket\Database\Repositories;


use arghavan\Ticket\Models\Reply;


class ReplyRepo
{

    public function store($ticket_id,$body,$media_id)
    {
        return Reply::query()->create([

            'user_id' => auth()->id(),
            'ticket_id' => $ticket_id,
            'media_id' => $media_id,
            'body' => $body
        ]);
    }
}
