<?php


namespace arghavan\Comment\Http\Controllers;


use App\Http\Controllers\Controller;
use arghavan\Comment\Events\CommentApprovedEvent;
use arghavan\Comment\Events\CommentRejectedEvent;
use arghavan\Comment\Events\CommentSubmittedEvent;
use arghavan\Comment\Http\Requests\CommentRequest;
use arghavan\Comment\Models\Comment;
use arghavan\Comment\Repositories\CommentRepo;
use arghavan\Common\Responses\AjaxResponses;
use arghavan\Course\Models\Course;
use arghavan\RolePermissions\Models\Permission;

class CommentController extends Controller
{
    public $repo;
    public function __construct(CommentRepo $repo)
    {
        $this->repo = $repo;
    }
    public function index()
    {
        $this->authorize('index', Comment::class);
        $comments = $this->repo
            ->searchBody(request('body'))
            ->searchEmail(request('email'))
            ->searchName(request('name'))
            ->searchStatus(request('status'));

            if(!auth()->user()->hasAnyPermission(Permission::PERMISSION_MANAGE_COMMENTS,
            Permission::PERMISSION_SUPER_ADMIN))
            {
                $comments->query->whereHasMorph('commentable',[Course::class],function ($query){
                    return $query->where('teacher_id',auth()->id());
                })->where('status',Comment::STATUS_APPROVED);
            }
            $comments = $comments->paginateParents();
        return view('Comments::index',compact('comments'));
    }

    public function show($commentId)
    {
        $comment = Comment::query()->where('id',$commentId)->with('commentable','user','comments')->firstOrFail();
        $this->authorize('view', $comment);
        return view('Comments::show',compact('comment'));
    }

    public function store(CommentRequest $request)
    {
        $comment = $this->repo->store($request->all());
        event(new CommentSubmittedEvent($comment));
        return back();
    }

    public function accept($id){

        $this->authorize('manage',Comment::class);
        $comment = $this->repo->findOrFail($id);
        if($this->repo->updateStatus($id,Comment::STATUS_APPROVED)){

            CommentApprovedEvent::dispatch($comment);
            return AjaxResponses::SuccessResponse();
        }

        return AjaxResponses::FaileResponse();

    }

    public function reject($id){

        $this->authorize('manage',Comment::class);
        $comment = $this->repo->findOrFail($id);
        if($this->repo->updateStatus($id,Comment::STATUS_REJECTED)){

            CommentRejectedEvent::dispatch($comment);
            return AjaxResponses::SuccessResponse();
        }

        return AjaxResponses::FaileResponse();

    }


    public function destroy($id,CommentRepo $repo)
    {
        $this->authorize('manage',Comment::class);
        $comment = $repo->findOrFail($id);
        $comment->delete();
        return AjaxResponses::SuccessResponse();
    }
}
