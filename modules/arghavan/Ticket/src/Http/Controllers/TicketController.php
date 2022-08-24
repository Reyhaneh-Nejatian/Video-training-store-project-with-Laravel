<?php


namespace arghavan\Ticket\Http\Controllers;


use App\Http\Controllers\Controller;
use arghavan\RolePermissions\Models\Permission;
use arghavan\Ticket\Database\Repositories\TicketRepo;
use arghavan\Ticket\Http\Requests\ReplyRequest;
use arghavan\Ticket\Http\Requests\TicketRequest;
use arghavan\Ticket\Models\Reply;
use arghavan\Ticket\Models\Ticket;
use arghavan\Ticket\Services\ReplyService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public $ticketRepo;
    public function __construct(TicketRepo $ticketRepo)
    {
        $this->ticketRepo = $ticketRepo;
    }
    public function index(Request $request)
    {
        if(auth()->user()->can(Permission::PERMISSION_MANAGE_TICKETS))
        {
            $tickets = $this->ticketRepo->joinUsers()
                ->searchEmail($request->email)
                ->searchName($request->name)
                ->searchTitle($request->title)
                ->searchDate(dateFromJalali($request->date))
                ->searchStatus($request->status)
                ->paginate();
        }else{
            $tickets = $this->ticketRepo->paginateAll(auth()->id());
        }

        return view('Tickets::index',compact('tickets'));
    }

    public function show($ticket)
    {
        $ticket = $this->ticketRepo->findOrFailWithReplies($ticket);
        $this->authorize('show',$ticket);
        return view("Tickets::show",compact('ticket'));
    }

    public function create()
    {
        return view('Tickets::create');
    }

    public function store(TicketRequest $request)
    {
        $ticket = $this->ticketRepo->store($request->title);

        ReplyService::store($ticket,$request->body,$request->attachment);

        newFeedback();

        return redirect()->route('tickets.index');

    }

    public function reply(Ticket $ticket,ReplyRequest $request)
    {
        $this->authorize('show',$ticket);
        ReplyService::store($ticket,$request->body,$request->attachment);
        newFeedback();

        return redirect()->route('tickets.show',$ticket->id);
    }

    public function close(Ticket $ticket)
    {
        $this->ticketRepo->setStatus($ticket->id,Ticket::STATUS_CLOSE);
        newFeedback();

        return redirect()->route('tickets.index');
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete',$ticket);
        $hasAttachments = Reply::query()->where('ticket_id',$ticket->id)
            ->whereNotNull('media_id')->with('media')->get();
        foreach ($hasAttachments as $reply)
        {
            $reply->media->delete();
        }
        $ticket->delete();
    }
}
