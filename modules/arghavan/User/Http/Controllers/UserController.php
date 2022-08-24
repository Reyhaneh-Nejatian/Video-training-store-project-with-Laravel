<?php

namespace arghavan\User\Http\Controllers;

use App\Http\Controllers\Controller;
use arghavan\Common\Responses\AjaxResponses;
use arghavan\Media\Services\MediaFileService;
use arghavan\RolePermissions\Repositories\RoleRepo;
use arghavan\User\Http\Requests\AddRoleRequest;
use arghavan\User\Http\Requests\UpdateProfileInformationRequest;
use arghavan\User\Http\Requests\UpdateUserPhoto;
use arghavan\User\Http\Requests\UpdateUserRequest;
use arghavan\User\Models\User;
use arghavan\User\Repositories\UserRepo;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public $userRepo;
    public $roleRepo;
    public function __construct(UserRepo $userRepo,RoleRepo $roleRepo){

        $this->userRepo = $userRepo;
        $this->roleRepo = $roleRepo;
    }
    public function index()
    {
        $this->authorize('index',User::class);
        $users = $this->userRepo->paginate();
        $roles = $this->roleRepo->all();
        return view('User::Admin.index',compact('users','roles'));
    }

    public function info($userId)
    {
        $this->authorize('index',User::class);
        $user = $this->userRepo->findByIdFullInfo($userId);
        return view('User::Admin.info',compact('user'));
    }

    public function addRole(AddRoleRequest $request, User $user){

        $this->authorize('addRole',User::class);
        $user->assignRole($request->role);
        newFeedback('موفقیت آمیز'," نقش کاربری {$request->role}  به کاربر {$user->name} داده شد.",'success');
        return back();
    }

    public function removeRole($userId,$role){

        $this->authorize('removeRole',User::class);
        $user = $this->userRepo->findById($userId);
        $user->removeRole($role);
        return AjaxResponses::SuccessResponse();
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit($userId)
    {
        $this->authorize('edit',User::class);
        $user = $this->userRepo->findById($userId);
        $roles = $this->roleRepo->all();
        return view('User::Admin.edit',compact('user','roles'));
    }


    public function update(UpdateUserRequest $request, $userId)
    {
        $this->authorize('edit',User::class);
        $user = $this->userRepo->findById($userId);
        if($request->hasFile('image')){

            $request->request->add(['image_id' => MediaFileService::publicUpload($request->file('image'))->id]);
            if($user->image){

                $user->image->delete();
            }

        }else{

            $request->request->add(['image_id' => $user->image_id]);
        }

        $this->userRepo->update($userId,$request);
        newFeedback();
        return redirect(route('users.index'));
    }

    public function updatePhoto(UpdateUserPhoto $request){

        $this->authorize('editProfile',User::class);
        $media = MediaFileService::publicUpload($request->file('userPhoto'));

        if(auth()->user()->image) auth()->user()->image->delete();

        auth()->user()->image_id = $media->id;

        auth()->user()->save();
        newFeedback();
        return back();
    }


    public function destroy($userId)
    {
        $user = $this->userRepo->findById($userId);
        $user->delete();
        return AjaxResponses::SuccessResponse();
    }

    public function manualVerify($userId){

//        dd('uu');
        $this->authorize('manualVerify',User::class);
        $user = $this->userRepo->findById($userId);
        $user->markEmailAsVerified();
        return AjaxResponses::SuccessResponse();
    }

    public function profile(){

        $this->authorize('editProfile',User::class);
        return view('User::Admin.profile');
    }

    public function updateProfile(UpdateProfileInformationRequest $request){

        $this->authorize('editProfile',User::class);
        $this->userRepo->updateProfile($request);
        newFeedback();
        return back();
    }
}
